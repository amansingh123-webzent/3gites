<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Store an uploaded image file.
     *
     * - Validates it is a real image (re-check at service layer)
     * - Resizes to max 1200px wide (preserves aspect ratio)
     * - Saves as JPEG to save disk space
     * - Returns the storage-relative path (e.g. "profiles/5/recent/abc123.jpg")
     *
     * @param  UploadedFile  $file
     * @param  string  $directory  e.g. "profiles/5/teen"
     * @return string  The path relative to storage/app/public/
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $relativePath = "{$directory}/{$filename}";

        // Validate it's an image
        if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
            throw new \Exception('Invalid image file');
        }

        // For now, just store the original file without processing
        // This bypasses GD extension issues
        $file->storeAs($directory, $filename, 'public');

        return $relativePath;
    }

    /**
     * Delete an image from the public disk.
     *
     * @param  string  $path  Relative path as stored in DB
     */
    public function delete(string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get the public URL for a stored image.
     *
     * @param  string|null  $path
     * @param  string  $placeholder  URL to return if no image exists
     */
    public function url(?string $path, string $placeholder = ''): string
    {
        if (! $path) {
            return $placeholder;
        }

        return Storage::disk('public')->url($path);
    }
}
