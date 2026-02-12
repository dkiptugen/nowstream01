<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\VideoDatatable;
use App\Http\Services\UploadService;
use App\Models\Content;
use App\Models\Event;
use App\Models\Tag;
use App\Traits\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class VideoController extends Controller
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
                return view('Backend.modules.videos.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     */
        public function create()
            {

                $this->data['events'] = Event::all();

                return view('Backend.modules.videos.add', $this->data);
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


                $video              = new Content();
                $video->channel_id  = Auth::user()->channel_id??0;
                $video->event_id    = $validatedData['event_id'];
                $video->slug        = Str::slug($validatedData['title']);
                $video->title       = $validatedData['title'];
                $video->description = $validatedData['description'];
                $video->type        = 'video';
                $video->content_group = 'video';

                // Handle thumbnail upload
                if ($request->hasFile('thumbnail'))
                    {
                        $uploadService    = new UploadService();
                        $thumbnailPath    = $uploadService->file_upload($request, 'thumbnail', 'thumbnails');
                        $video->thumbnail_url = $thumbnailPath['path'];
                    }

                // Handle video upload
                if ($request->hasFile('video_path'))
                    {
                        $uploadService     = new UploadService();
                        $videoPath         = $uploadService->file_upload($request, 'video_path', 'videos');
                        $video->content_path = $videoPath['path'];
                    }

                $video->system_user_id = $request->user('admin')->id;
                $result                = $video->save();

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
                        $video->tags = json_encode($tags); // Save tag IDs as a JSON array in the tags column
                        $video->save();
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
        public function edit(Content $video)
            {
                $this->data['video']  = $video->load('tags');
                $this->data['tags']   = Tag::all();
                $this->data['events'] = Event::all();
                $tagIdsString         = $this->data['video']->tags;
                $tagIdsArray          = json_decode($tagIdsString, true); // Convert the JSON string to an array

                $this->data['selectedTagIds'] = Tag::whereIn('id', $tagIdsArray ?? [])->pluck('id')->toArray();


                return view('Backend.modules.videos.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Content $video)
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

                if (!$video)
                    {
                        return self::failed('Videos', 'Video not found.', route('video.index'));
                    }

                // Update video details
                $video->title       = $validatedData['title'];
                $video->description = $validatedData['description'];
                $video->event_id    = $validatedData['event_id'];

                // Handle thumbnail upload
                if ($request->hasFile('thumbnail'))
                    {
                        $uploadService   = new UploadService();
                        $thumbnailUpload = $uploadService->file_upload($request, 'thumbnail', 'video_thumbnail');
                        if ($thumbnailUpload)
                            {
                                $video->thumbnail_url = $thumbnailUpload['path'];
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
                        $videoUpload   = $uploadService->file_upload($request, 'video_path', 'videos');
                        if ($videoUpload)
                            {
                                $video->content_path = $videoUpload['path'];
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
                        $video->tags()->sync($tags);
                    }


                // Save the video
                $result = $video->save();
                if ($result)
                    {
                        return self::success('Channel videos', 'Video updated successfully.', route('video.index'));
                    }
                else
                    {
                        Log::error('Video update failed', ['video' => $video]);
                        return self::failed('Channel videos', 'Video not updated.', route('video.index'));
                    }
            }


    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Content $video)
            {
                $result = $video->delete();
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
        public function datatable(Request $request, VideoDatatable $datatable)
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
