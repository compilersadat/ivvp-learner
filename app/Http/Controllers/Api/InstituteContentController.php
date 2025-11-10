<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InstituteContentController extends Controller
{
    /**
     * Stream the requested content file without leaking the underlying S3 URL.
     */
    public function download(Content $content): Response
    {
        $upload = $content->fileUpload;

        if (! $upload || ! $upload->url) {
            return response()->json(['message' => 'File not linked to this content.'], Response::HTTP_NOT_FOUND);
        }

        $path = $this->normalizePath($upload->url);

        if (! $path) {
            return response()->json(['message' => 'Stored file path is invalid.'], Response::HTTP_NOT_FOUND);
        }

        $disk = Storage::disk('s3');

        if (! $disk->exists($path)) {
            return response()->json(['message' => 'Stored file is missing.'], Response::HTTP_NOT_FOUND);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $baseName = Str::slug($content->title ?? 'content');
        $fileName = $extension ? "{$baseName}.{$extension}" : $baseName;
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return $disk->response($path, $fileName, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    protected function normalizePath(?string $rawPath): ?string
    {
        if (! $rawPath) {
            return null;
        }

        $parsed = parse_url($rawPath, PHP_URL_PATH);
        $path = $parsed ?: $rawPath;

        return ltrim($path, '/');
    }
}
