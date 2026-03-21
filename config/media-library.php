<?php

return [
    'disk' => env('MEDIA_LIBRARY_DISK', 'akamai'),
    'directory' => env('MEDIA_LIBRARY_DIRECTORY', 'media-library'),
    'max_items' => env('MEDIA_LIBRARY_MAX_ITEMS', 100),
    'max_upload_kb' => env('MEDIA_LIBRARY_MAX_UPLOAD_KB', 51200),
    'allowed_mimetypes' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg+xml',
        'video/mp4',
        'video/webm',
        'audio/mpeg',
        'audio/mp4',
        'audio/wav',
        'application/pdf',
        'text/plain',
    ],
];
