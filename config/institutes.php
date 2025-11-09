<?php

return [
    'usb_key' => [
        /*
        |--------------------------------------------------------------------------
        | Pendrive mount path
        |--------------------------------------------------------------------------
        |
        | This is the directory where the USB key file will be written whenever
        | an institute is created. Point this to the mounted pendrive path on
        | the server (e.g. /media/usb or /Volumes/IVVP). When left empty the
        | application falls back to storage/app/usb-keys.
        |
        */
        'directory' => env('INSTITUTE_USB_KEY_DIRECTORY', storage_path('app/usb-keys')),

        /*
        |--------------------------------------------------------------------------
        | Filename pattern
        |--------------------------------------------------------------------------
        |
        | File name used when persisting the key. The placeholders :id, :slug and
        | :identifier can be used to generate readable names for each institute.
        |
        */
        'filename_pattern' => env('INSTITUTE_USB_KEY_FILENAME', 'institute-:id.key'),
    ],
];
