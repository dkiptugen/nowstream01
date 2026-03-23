<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaLibraryFileRequest;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryController extends Controller
    {
        public function index(Request $request): JsonResponse
            {
                $search = trim((string)$request->string('search')->value());

                $items = Media::query()
                              ->when($search !== '', function ($query) use ($search)
                                  {
                                      $query->where('name', 'like', '%' . $search . '%');
                                  })
                              ->latest()
                              ->take((int)config('media-library.max_items', 100))
                              ->get()
                              ->map(fn(Media $media): array => $this->formatItem($media))
                              ->values()
                              ->all();

                return response()->json([
                    'data' => $items,
                ]);
            }

        public function store(StoreMediaLibraryFileRequest $request): JsonResponse
            {
                $uploadedFile   = $request->file('file');
                $disk           = (string)config('media-library.disk', 'akamai');
                $directory      = trim((string)config('media-library.directory', 'media-library'), '/');
                $extension      = $uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension() ?: 'bin';
                $storedFilename = Str::uuid()->toString() . '.' . $extension;
                $path           = trim($directory . '/' . date('Y/m') . '/' . $storedFilename, '/');

                Storage::disk($disk)->putFileAs(
                    dirname($path),
                    $uploadedFile,
                    basename($path),
                    ['visibility' => 'public']
                );

                $media = Media::create([
                    'name'          => $storedFilename,
                    'original_name' => $uploadedFile->getClientOriginalName(),
                    'disk'          => $disk,
                    'directory'     => $directory,
                    'path'          => $path,
                    'url'           => Storage::disk($disk)->url($path),
                    'mime_type'     => $uploadedFile->getClientMimeType() ?: 'application/octet-stream',
                    'extension'     => $extension,
                    'size'          => $uploadedFile->getSize(),
                    'type'          => $this->resolveType($uploadedFile->getClientMimeType() ?: 'application/octet-stream'),
                    'is_image'      => Str::startsWith((string)$uploadedFile->getClientMimeType(), 'image/'),
                ]);

                $item = $this->formatItem($media);

                return response()->json([
                    'message' => 'Media uploaded successfully.',
                    'data'    => $item,
                    'url'     => $item['url'],
                ], 201);
            }

        protected function formatItem(Media $media): array
            {
                return [
                    'name'        => $media->original_name ?: $media->name,
                    'path'        => $media->path,
                    'url'         => $media->url,
                    'size'        => $media->size,
                    'mime_type'   => $media->mime_type,
                    'type'        => $media->type,
                    'uploaded_at' => optional($media->created_at)->toIso8601String(),
                    'is_image'    => (bool)$media->is_image,
                ];
            }

        protected function resolveType(string $mimeType): string
            {
                return match (true)
                    {
                    Str::startsWith($mimeType, 'image/') => 'image',
                    Str::startsWith($mimeType, 'video/') => 'video',
                    Str::startsWith($mimeType, 'audio/') => 'audio',
                    default                              => 'document',
                    };
            }
    }
