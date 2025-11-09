<?php

namespace App\Services;

use App\Models\Institute;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class InstituteUsbKeyService
{
    /**
     * Persist the institute USB identifier to the configured pendrive path.
     *
     * @return array{success: bool, path: string|null, message: string|null}
     */
    public function writeKeyFile(Institute $institute): array
    {
        if (! $institute->usb_identifier) {
            return [
                'success' => false,
                'path' => null,
                'message' => 'Institute is missing a USB identifier.',
            ];
        }

        $directory = rtrim((string) config('institutes.usb_key.directory'), DIRECTORY_SEPARATOR);

        if ($directory === '') {
            return [
                'success' => false,
                'path' => null,
                'message' => 'Pendrive path is not configured.',
            ];
        }

        try {
            File::ensureDirectoryExists($directory);
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'path' => null,
                'message' => 'Unable to prepare pendrive directory.',
            ];
        }

        $path = $directory . DIRECTORY_SEPARATOR . $this->buildFileName($institute);

        try {
            File::put($path, $this->buildFileContents($institute));
        } catch (Throwable $exception) {
            report($exception);

            return [
                'success' => false,
                'path' => null,
                'message' => 'Failed to write USB key file.',
            ];
        }

        return [
            'success' => true,
            'path' => $path,
            'message' => null,
        ];
    }

    protected function buildFileName(Institute $institute): string
    {
        $pattern = (string) config('institutes.usb_key.filename_pattern', 'institute-:id.key');

        return str_replace(
            [':id', ':identifier', ':slug'],
            [
                $institute->id ?? 'new',
                $institute->usb_identifier,
                Str::slug($institute->name ?? 'institute'),
            ],
            $pattern
        );
    }

    protected function buildFileContents(Institute $institute): string
    {
        $lines = [
            'INSTITUTE_NAME="' . $institute->name . '"',
            'USB_IDENTIFIER="' . $institute->usb_identifier . '"',
            'GENERATED_AT="' . now()->toIso8601String() . '"',
        ];

        if ($institute->email) {
            $lines[] = 'EMAIL="' . $institute->email . '"';
        }

        if ($institute->phone) {
            $lines[] = 'PHONE="' . $institute->phone . '"';
        }

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}
