<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\S3upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class InstituteContentController extends Controller
{
    /**
     * Stream the requested content file without leaking the underlying S3 URL.
     */
    public function download(Request $request, Content $content): Response
    {
        $fileUrl = $this->resolveFileUrl($content);

        if (! $fileUrl) {
            return response()->json(['message' => 'File not linked to this content.'], Response::HTTP_NOT_FOUND);
        }

        $path = $this->normalizePath($fileUrl);

        $disk = Storage::disk('s3');
        $extension = pathinfo($path ?? $fileUrl, PATHINFO_EXTENSION);
        $baseName = Str::slug($content->title ?? 'content');
        $fileName = $extension ? "{$baseName}.{$extension}" : $baseName;

        if ($path && $disk->exists($path)) {
            $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

            return $disk->response($path, $fileName, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ]);
        }

        if (filter_var($fileUrl, FILTER_VALIDATE_URL)) {
            $headers = [];
            if ($range = $request->header('Range')) {
                $headers['Range'] = $range;
            }

            $remote = Http::withOptions(['stream' => true])
                ->withHeaders($headers)
                ->get($fileUrl);

            if ($remote->successful()) {
                $mimeType = $remote->header('content-type') ?? 'application/octet-stream';
                $psrStream = $remote->toPsrResponse()->getBody();

                return response()->stream(function () use ($psrStream) {
                    while (! $psrStream->eof()) {
                        echo $psrStream->read(1024 * 32);
                    }
                }, $remote->status(), array_filter([
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                    'Cache-Control' => 'private, max-age=0, must-revalidate',
                    'Content-Length' => $remote->header('content-length'),
                    'Content-Range' => $remote->header('content-range'),
                    'Accept-Ranges' => $remote->header('accept-ranges'),
                ]));
            }
        }

        return response()->json(['message' => 'Stored file is missing.'], Response::HTTP_NOT_FOUND);
    }

    protected function resolveFileUrl(Content $content): ?string
    {
        if ($content->relationLoaded('fileUpload') && $content->fileUpload && $content->fileUpload->url) {
            return $content->fileUpload->url;
        }

        if ($content->fileUpload && $content->fileUpload->url) {
            return $content->fileUpload->url;
        }

        if (! $content->file_url) {
            return null;
        }

        if (filter_var($content->file_url, FILTER_VALIDATE_URL)) {
            return $content->file_url;
        }

        return S3upload::where('id', $content->file_url)->value('url');
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
