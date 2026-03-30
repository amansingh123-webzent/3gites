<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tribute;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTributeController extends Controller
{
    public function __construct(private ImageUploadService $imageService) {}

    /**
     * GET /admin/tributes/{tribute}/edit
     */
    public function edit(Tribute $tribute): View
    {
        return view('admin.tributes.edit', compact('tribute'));
    }

    /**
     * PATCH /admin/tributes/{tribute}
     */
    public function update(Request $request, Tribute $tribute): RedirectResponse
    {
        $validated = $request->validate([
            'member_name'  => ['required', 'string', 'max:255'],
            'birth_year'   => ['nullable', 'integer', 'min:1920', 'max:1985'],
            'death_year'   => ['nullable', 'integer', 'min:1920', 'max:2100'],
            'tribute_text' => [
                'required',
                'string',
                // Enforce ≤ 250 words at server side
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count(strip_tags($value));
                    if ($wordCount > 250) {
                        $fail("The tribute text must not exceed 250 words. Current: {$wordCount} words.");
                    }
                },
            ],
        ]);

        $tribute->update($validated);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($tribute)
            ->log("Tribute updated for: {$tribute->member_name}");

        return redirect()->route('tributes.show', $tribute)
            ->with('success', 'Tribute page updated.');
    }

    /**
     * POST /admin/tributes/{tribute}/photo
     */
    public function uploadPhoto(Request $request, Tribute $tribute): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
        ]);

        if ($tribute->photo) {
            $this->imageService->delete($tribute->photo);
        }

        $path = $this->imageService->store(
            $request->file('photo'),
            "tributes/{$tribute->id}"
        );

        $tribute->update(['photo' => $path]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($tribute)
            ->log("Tribute photo uploaded for: {$tribute->member_name}");

        return back()->with('success', 'Tribute photo updated.');
    }
}
