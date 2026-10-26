<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfilePhotoController extends Controller
{
    public function __invoke(string $filename): StreamedResponse
    {
        abort_unless(preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9._-]*\z/', $filename) === 1, 404);

        $path = 'foto-profil/'.$filename;
        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        $mimeType = $disk->mimeType($path);
        abort_unless(is_string($mimeType) && str_starts_with($mimeType, 'image/'), 404);

        return $disk->response($path, null, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=0, must-revalidate',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
