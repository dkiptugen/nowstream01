<?php

    namespace App\Http\Services;

    use App\Services\ProcessImage;
    use Illuminate\Http\Testing\MimeType;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;


    class UploadService
        {
            public $baseDimension = 1200;

            public function file_upload($request, $name, $type, $disk = null)
                {
                    if (is_null($disk))
                        {
                            $disk = config('filesystems.default');
                        }

                    $file       = $request->file($name);
                    $filename   = $file->getClientOriginalName();
                    $mime       = $file->getClientMimeType();
                    $size       = number_format(($file->getSize() / 1024), 2) . 'Kb';
                    $path       = Storage::disk($disk)->putFileAs('nowstream/' . $type . '/' . date('Y/m'), $file, $filename, 'public');
                    $processor  = new ProcessImage(save_loc: 'nowstream/' . $type . '/' . date('Y/m') . '/' . $filename, type: 'portrait');
                    $posterPath = $processor->execute($request->file($name)->getRealPath(), $disk, true);
                    Log::error($posterPath);
                    return [
                        'path' => $posterPath,
                        'name' => $filename,
                        'mime' => $mime,
                        'size' => $size
                    ];
                }

        }
