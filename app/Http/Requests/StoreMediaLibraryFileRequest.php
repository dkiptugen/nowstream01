<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaLibraryFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:' . (int) config('media-library.max_upload_kb', 51200),
                'mimetypes:' . implode(',', config('media-library.allowed_mimetypes', [
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
                ])),
            ],
        ];
    }
}
