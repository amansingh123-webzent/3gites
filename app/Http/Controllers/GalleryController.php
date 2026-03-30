<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GalleryController extends Controller
{
    // Enforce limits
    private const ADMIN_GALLERY_LIMIT  = 500;
    private const MEMBER_GALLERY_LIMIT = 50;

    public function __construct(private ImageUploadService $imageService) {}

    // ── Public gallery index ──────────────────────────────────────────────────

    /**
     * GET /gallery
     *
     * Renders two tabs:
     *   - "Class Gallery"    — admin-uploaded photos (paginated, 24/page)
     *   - "Member Galleries" — list of members who have photos
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'class'); // 'class' | 'members'

        // Class (admin) gallery — paginated
        $adminPhotos = Photo::with('user')
            ->where('is_admin_gallery', true)
            ->latest()
            ->paginate(24, ['*'], 'page')
            ->withQueryString();

        $adminTotal = Photo::where('is_admin_gallery', true)->count();

        // All member photos for direct display (paginated)
        $memberPhotos = Photo::with('user.profile')
            ->where('is_admin_gallery', false)
            ->latest()
            ->paginate(24, ['*'], 'memberPage')
            ->withQueryString();

        // Total photos across all member galleries
        $totalMemberPhotos = Photo::where('is_admin_gallery', false)->count();

        // Admin gallery capacity info
        $adminCapacity = [
            'used'      => $adminTotal,
            'remaining' => max(0, self::ADMIN_GALLERY_LIMIT - $adminTotal),
            'limit'     => self::ADMIN_GALLERY_LIMIT,
            'percent'   => min(100, round(($adminTotal / self::ADMIN_GALLERY_LIMIT) * 100)),
        ];

        // Member's own photo count (for upload UI, if logged in)
        $myPhotoCount = auth()->check()
            ? Photo::where('user_id', auth()->id())->where('is_admin_gallery', false)->count()
            : 0;

        return view('gallery.index', compact(
            'tab',
            'adminPhotos',
            'adminCapacity',
            'memberPhotos',
            'myPhotoCount',
            'totalMemberPhotos',
        ));
    }

    /**
     * GET /gallery/member/{user}
     * An individual member's full gallery (paginated).
     */
    public function memberGallery(User $user, Request $request): View
    {
        $photos = Photo::where('user_id', $user->id)
            ->where('is_admin_gallery', false)
            ->latest()
            ->paginate(24)
            ->withQueryString();

        $total = Photo::where('user_id', $user->id)
            ->where('is_admin_gallery', false)
            ->count();

        return view('gallery.member', compact('user', 'photos', 'total'));
    }

    // ── Member upload ─────────────────────────────────────────────────────────

    /**
     * POST /gallery/upload
     * Authenticated members upload to their personal gallery.
     */
    public function upload(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Check limit
        $count = Photo::where('user_id', $user->id)
            ->where('is_admin_gallery', false)
            ->count();

        if ($count >= self::MEMBER_GALLERY_LIMIT) {
            return back()->with('error',
                "You have reached the {$count}-photo limit for your personal gallery. "
                . 'Please delete some photos to make room.'
            );
        }

        $request->validate([
            'photo'   => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:500'],
        ]);

        $path = $this->imageService->store(
            $request->file('photo'),
            "gallery/members/{$user->id}"
        );

        Photo::create([
            'user_id'         => $user->id,
            'file_path'       => $path,
            'caption'         => $request->caption,
            'is_admin_gallery' => false,
        ]);

        activity()
            ->causedBy($user)
            ->log('Photo uploaded to personal gallery');

        return back()->with('success', 'Photo added to your gallery.');
    }

    // ── Admin upload ──────────────────────────────────────────────────────────

    /**
     * POST /gallery/admin/upload
     * Admin uploads to the shared class gallery.
     */
    public function adminUpload(Request $request): RedirectResponse
    {
        $count = Photo::where('is_admin_gallery', true)->count();

        if ($count >= self::ADMIN_GALLERY_LIMIT) {
            return back()->with('error',
                "The class gallery has reached its limit of {self::ADMIN_GALLERY_LIMIT} photos."
            );
        }

        $request->validate([
            'photo'   => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'caption' => ['nullable', 'string', 'max:500'],
        ]);

        $path = $this->imageService->store(
            $request->file('photo'),
            'gallery/admin'
        );

        Photo::create([
            'user_id'          => auth()->id(),
            'file_path'        => $path,
            'caption'          => $request->caption,
            'is_admin_gallery' => true,
        ]);

        activity()
            ->causedBy(auth()->user())
            ->log('Photo uploaded to class gallery');

        return back()->with('success', 'Photo added to the class gallery.');
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    /**
     * DELETE /gallery/photos/{photo}
     * Owner or admin can delete a photo.
     */
    public function destroy(Photo $photo): RedirectResponse
    {
        Gate::authorize('delete', $photo);

        // Remove file from disk
        $this->imageService->delete($photo->file_path);

        $photo->delete();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($photo)
            ->log('Photo deleted from gallery');

        return back()->with('success', 'Photo removed.');
    }
}
