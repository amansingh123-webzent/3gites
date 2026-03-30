<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private ImageUploadService $imageService) {}

    // ── Edit profile ─────────────────────────────────────────────────────────

    /**
     * GET /members/{user}/edit
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $user->load('profile', 'birthday');

        // Create profile row if it somehow doesn't exist
        if (! $user->profile) {
            $user->profile()->create([]);
            $user->refresh()->load('profile');
        }

        return view('members.edit', compact('user'));
    }

    /**
     * PATCH /members/{user}
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'bio'             => ['nullable', 'string', 'max:2000'],
            'career'          => ['nullable', 'string', 'max:1000'],
            'family_info'     => ['nullable', 'string', 'max:1000'],
            'retirement_info' => ['nullable', 'string', 'max:1000'],
            // Birthday fields
            'birth_month'     => ['nullable', 'integer', 'min:1', 'max:12'],
            'birth_day'       => ['nullable', 'integer', 'min:1', 'max:31'],
            'birth_year'      => ['nullable', 'integer', 'min:1920', 'max:1980'],
            // Allow admin to update basic user info too
            'phone'           => ['nullable', 'string', 'max:30'],
            // Photo uploads
            'teen_photo'      => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'], // 5MB
            'recent_photo'    => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'], // 5MB
        ]);

        // Update profile fields
        $profileData = [
            'bio'             => $validated['bio'] ?? null,
            'career'          => $validated['career'] ?? null,
            'family_info'     => $validated['family_info'] ?? null,
            'retirement_info' => $validated['retirement_info'] ?? null,
        ];

        // Handle photo uploads
        if ($request->hasFile('teen_photo')) {
            $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
            
            // Delete old teen photo if exists
            if ($profile->teen_photo) {
                $this->imageService->delete($profile->teen_photo);
            }
            
            $profileData['teen_photo'] = $this->imageService->store(
                $request->file('teen_photo'),
                "profiles/{$user->id}/teen"
            );
        }

        if ($request->hasFile('recent_photo')) {
            $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
            
            // Delete old recent photo if exists
            if ($profile->recent_photo) {
                $this->imageService->delete($profile->recent_photo);
            }
            
            $profileData['recent_photo'] = $this->imageService->store(
                $request->file('recent_photo'),
                "profiles/{$user->id}/recent"
            );
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Update user phone if changed
        if (array_key_exists('phone', $validated)) {
            $user->update(['phone' => $validated['phone']]);
        }

        // Update birthday
        if (! empty($validated['birth_month']) && ! empty($validated['birth_day'])) {
            $user->birthday()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'birth_month' => $validated['birth_month'],
                    'birth_day'   => $validated['birth_day'],
                    'birth_year'  => $validated['birth_year'] ?? null,
                ]
            );
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Profile updated');

        $message = 'Your profile has been updated.';
        
        // Add photo upload info to success message
        if ($request->hasFile('teen_photo') || $request->hasFile('recent_photo')) {
            $uploaded = [];
            if ($request->hasFile('teen_photo')) $uploaded[] = 'teen photo';
            if ($request->hasFile('recent_photo')) $uploaded[] = 'recent photo';
            
            if (!empty($uploaded)) {
                $message .= ' ' . ucfirst(implode(' and ', $uploaded)) . ' uploaded successfully.';
            }
        }

        return redirect()->route('members.show', $user)
            ->with('success', $message);
    }

    // ── Photo uploads ─────────────────────────────────────────────────────────

    /**
     * POST /members/{user}/photo/teen
     */
    public function uploadTeenPhoto(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('uploadTeenPhoto', $user);

        $request->validate([
            'teen_photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
            ],
        ]);

        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        // Delete old file if exists
        if ($profile->teen_photo) {
            $this->imageService->delete($profile->teen_photo);
        }

        $path = $this->imageService->store(
            $request->file('teen_photo'),
            "profiles/{$user->id}/teen"
        );

        $profile->update(['teen_photo' => $path]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Teen photo uploaded');

        return back()->with('success', 'Teen photo updated successfully.');
    }

    /**
     * POST /members/{user}/photo/recent
     */
    public function uploadRecentPhoto(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('uploadRecentPhoto', $user);

        $request->validate([
            'recent_photo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048',
            ],
        ]);

        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        if ($profile->recent_photo) {
            $this->imageService->delete($profile->recent_photo);
        }

        $path = $this->imageService->store(
            $request->file('recent_photo'),
            "profiles/{$user->id}/recent"
        );

        $profile->update(['recent_photo' => $path]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Recent photo uploaded');

        return back()->with('success', 'Recent photo updated successfully.');
    }

    /**
     * DELETE /members/{user}/photo/{type}
     * type: teen | recent
     */
    public function deletePhoto(Request $request, User $user, string $type): RedirectResponse
    {
        Gate::authorize('deletePhoto', $user);

        abort_unless(in_array($type, ['teen', 'recent']), 404);

        $profile = $user->profile;

        if (! $profile) {
            return back()->with('error', 'No profile found.');
        }

        $column = $type . '_photo'; // teen_photo | recent_photo

        if ($profile->$column) {
            $this->imageService->delete($profile->$column);
            $profile->update([$column => null]);
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log("Deleted {$type} photo");

        return back()->with('success', ucfirst($type) . ' photo removed.');
    }

    // ── Password change (first login, from Module 2) ──────────────────────────

    public function showChangePassword(): View
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        activity()
            ->causedBy($request->user())
            ->log('Password changed');

        return redirect()->route('dashboard')
            ->with('success', 'Password updated. Welcome to 3Gites-1975!');
    }
}
