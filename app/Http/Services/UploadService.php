<?php

    namespace App\Http\Services;

    use Illuminate\Http\Testing\MimeType;
    use Illuminate\Support\Facades\Storage;


    class UploadService
        {
            public function file_upload($request, $name, $type, $disk)
                {
                    $file     = $request->file($name);
                    $filename = $file->getClientOriginalName();
                    $mime     = $file->getClientMimeType();
                    $size     = number_format(($file->getSize() / 1024), 2) . 'Kb';
                    $path     = Storage::disk($disk)->putFileAs('nowstream/'.$type . '/' . date('Y/m'), $file, $filename, 'public');
                    return [
                        'path' => $path,
                        'name' => $filename,
                        'mime' => $mime,
                        'size' => $size
                    ];
                }

        }
