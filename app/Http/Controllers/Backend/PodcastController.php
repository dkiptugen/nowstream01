<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\PodcastDatatable;
use App\Http\Services\UploadService;
use App\Models\Category;
use App\Models\Content;
use App\Models\Region;
use App\Models\Tag;
use App\Traits\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PodcastController extends Controller
{
        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }

    /**
     * Display a listing of the resource.
     */
        public function index()
            {
                return view('Backend.modules.podcasts.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     */
        public function create()
            {

                return view('Backend.modules.podcasts.add', $this->data);
            }

        public function store(Request $request)
            {
                $validatedData = $request->validate([
                        'title'       => 'required|string|max:255',
                        'description' => 'required|string',
                        'event_id'    => 'required|exists:events,uuid',
                        'thumbnail'   => 'nullable|image|max:5120',
                        'video_path'  => 'required|file|mimes:mp4,mov,avi,mpeg|max:81200',
                        'tags'        => 'nullable|array',
                        'tags.*'      => 'nullable|string|max:50',
                    ]
                );


                //$event = Event::findOrFail($validatedData['event_id']);


                $podcast              = new Content();
                $podcast->channel_id  = $request->user()->channel_id??0;
                $podcast->event_id    = $validatedData['event_id'];
                $podcast->slug        = Str::slug($validatedData['title']);
                $podcast->title       = $validatedData['title'];
                $podcast->description = $validatedData['description'];

                // Handle thumbnail upload
                if ($request->hasFile('thumbnail'))
                    {
                        $uploadService    = new UploadService();
                        $thumbnailPath    = $uploadService->file_upload($request, 'thumbnail', 'thumbnails');
                        $podcast->thumbnail_url = $thumbnailPath['path'];
                    }

                // Handle video upload
                if ($request->hasFile('video_path'))
                    {
                        $uploadService     = new UploadService();
                        $podcastPath         = $uploadService->file_upload($request, 'video_path', 'videos');
                        $podcast->content_path = $podcastPath['path'];
                    }

                $podcast->system_user_id = $request->user('admin')->id;
                $result                = $podcast->save();

                // Save tags to the tags table and attach tag IDs to the tags column of the videos table
                if ($result && isset($validatedData['tags']))
                    {
                        $tags = [];
                        foreach ($validatedData['tags'] as $tagName)
                            {
                                $tag    = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]
                                );
                                $tags[] = $tag->id;
                            }
                        $podcast->tags = json_encode($tags); // Save tag IDs as a JSON array in the tags column
                        $podcast->save();
                    }

                if ($result)
                    {
                        return self::success('Channel videos', 'Video saved successfully.',
                            route('video.index')
                        );
                    }

                return self::failed('Channel videos', 'Video not saved.',
                    route('video.index')
                );
            }


    /**
     * Display the specified resource.
     */
        public function show(string $id)
            {
                //
            }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Content $podcast)
            {
                $this->data['category'] = Category::get();
                $this->data['region'] = Region::get();
                $this->data['podcast']  = $podcast->load('tags','categories');
                return view('Backend.modules.podcasts.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Content $podcast)
            {


                // Validate the incoming request
                $validatedData = $request->validate([
                        'title'       => 'required|string|max:255',
                        'description' => 'required|string',
                        'event_id'    => 'required|exists:events,uuid',
                        'thumbnail'   => 'nullable|image|max:5120',
                        'video_path'  => 'nullable|file|mimetypes:video/avi,video/mpeg,video/mp4|max:50000',
                        'tags'        => 'nullable|array',
                        'tags.*'      => 'nullable|string|max:50',
                    ]
                );

                // Find the video by ID

                if (!$podcast)
                    {
                        return self::failed('Videos', 'Video not found.', route('video.index'));
                    }

                // Update video details
                $podcast->title       = $validatedData['title'];
                $podcast->description = $validatedData['description'];
                $podcast->event_id    = $validatedData['event_id'];

                // Handle thumbnail upload
                if ($request->hasFile('thumbnail'))
                    {
                        $uploadService   = new UploadService();
                        $thumbnailUpload = $uploadService->file_upload($request, 'thumbnail', 'video_thumbnail');
                        if ($thumbnailUpload)
                            {
                                $podcast->thumbnail_url = $thumbnailUpload['path'];
                            }
                        else
                            {
                                Log::error('Thumbnail upload failed');
                            }
                    }

                // Handle video upload
                if ($request->hasFile('video_path'))
                    {
                        $uploadService = new UploadService();
                        $podcastUpload   = $uploadService->file_upload($request, 'video_path', 'videos');
                        if ($podcastUpload)
                            {
                                $podcast->content_path = $podcastUpload['path'];
                            }
                        else
                            {
                                Log::error('Video upload failed');
                            }
                    }

                // Update tags
                if (isset($validatedData['tags']))
                    {
                        $tags = [];
                        foreach ($validatedData['tags'] as $tagName)
                            {
                                $tag    = Tag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]
                                );
                                $tags[] = $tag->id;
                            }
                        $podcast->tags()->sync($tags);
                    }


                // Save the video
                $result = $podcast->save();
                if ($result)
                    {
                        return self::success('Channel videos', 'Video updated successfully.', route('video.index'));
                    }
                else
                    {
                        Log::error('Video update failed', ['video' => $podcast]);
                        return self::failed('Channel videos', 'Video not updated.', route('video.index'));
                    }
            }


    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Content $podcast)
            {
                $result = $podcast->delete();
                if ($result)
                    {
                        return self::success('Videos', 'Video deleted successfully.',
                            route('video.index')
                        );
                    }
                else
                    {

                        return self::failed('Channel videos', 'Video not deleted.',
                            route('video.index')
                        );
                    }
            }


    /**
     * Custom method added for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function datatable(Request $request, PodcastDatatable $datatable)
            {
                $datatable->columns = [
                    0 => 'id',
                    1 => 'title',
                    2 => 'description',
                    6 => 'created_at'
                ];
                return response()->json($datatable->data($request));
            }
}
