<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
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
        $upload = $content->fileUpload;

        if (! $upload || ! $upload->url) {
            return response()->json(['message' => 'File not linked to this content.'], Response::HTTP_NOT_FOUND);
        }

        $path = $this->normalizePath($upload->url);

        if (! $path) {
            return response()->json(['message' => 'Stored file path is invalid.'], Response::HTTP_NOT_FOUND);
        }

        $disk = Storage::disk('s3');
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $baseName = Str::slug($content->title ?? 'content');
        $fileName = $extension ? "{$baseName}.{$extension}" : $baseName;

        if ($disk->exists($path)) {
            $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

            return $disk->response($path, $fileName, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ]);
        }

        if (filter_var($upload->url, FILTER_VALIDATE_URL)) {
            $headers = [];
            if ($range = $request->header('Range')) {
                $headers['Range'] = $range;
            }

            $remote = Http::withOptions(['stream' => true])
                ->withHeaders($headers)
                ->get($upload->url);

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
