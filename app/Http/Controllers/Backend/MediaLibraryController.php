<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaLibraryFileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

class MediaLibraryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $directory = $this->absoluteDirectory();
        $search = trim((string) $request->string('search')->value());

        File::ensureDirectoryExists($directory);

        $items = collect(File::allFiles($directory))
            ->when($search !== '', function ($files) use ($search) {
                return $files->filter(static function (SplFileInfo $file) use ($search): bool {
                    return str_contains(Str::lower($file->getFilename()), Str::lower($search));
                });
            })
            ->sortByDesc(static fn (SplFileInfo $file): int => $file->getMTime())
            ->take((int) config('media-library.max_items', 100))
            ->map(fn (SplFileInfo $file): array => $this->formatItem($file))
            ->values()
            ->all();

        return response()->json([
            'data' => $items,
        ]);
    }

    public function store(StoreMediaLibraryFileRequest $request): JsonResponse
    {
        $uploadedFile = $request->file('file');
        $directory = $this->absoluteDirectory();

        File::ensureDirectoryExists($directory);

        $extension = $uploadedFile->getClientOriginalExtension() ?: $uploadedFile->extension() ?: 'bin';
        $storedFilename = Str::uuid()->toString().'.'.$extension;

        $uploadedFile->move($directory, $storedFilename);

        $item = $this->formatItem(new SplFileInfo($directory.DIRECTORY_SEPARATOR.$storedFilename, '', $storedFilename));

        return response()->json([
            'message' => 'Media uploaded successfully.',
            'data' => $item,
            'url' => $item['url'],
        ], 201);
    }

    protected function formatItem(SplFileInfo $file): array
    {
        $relativePath = ltrim(str_replace($this->absoluteDirectory(), '', $file->getPathname()), DIRECTORY_SEPARATOR);
        $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
        $publicDirectory = trim((string) config('media-library.directory', 'media-library'), '/');
        $mimeType = File::mimeType($file->getPathname()) ?: 'application/octet-stream';

        return [
            'name' => $file->getFilename(),
            'path' => $relativePath,
            'url' => asset($publicDirectory.'/'.$relativePath),
            'size' => $file->getSize(),
            'mime_type' => $mimeType,
            'type' => $this->resolveType($mimeType),
            'uploaded_at' => Carbon::createFromTimestamp($file->getMTime())->toIso8601String(),
            'is_image' => Str::startsWith($mimeType, 'image/'),
        ];
    }

    protected function resolveType(string $mimeType): string
    {
        return match (true) {
            Str::startsWith($mimeType, 'image/') => 'image',
            Str::startsWith($mimeType, 'video/') => 'video',
            Str::startsWith($mimeType, 'audio/') => 'audio',
            default => 'document',
        };
    }

    protected function absoluteDirectory(): string
    {
        return public_path(trim((string) config('media-library.directory', 'media-library'), '/'));
    }
}
