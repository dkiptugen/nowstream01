<?php
	
	namespace App\Http\Controllers\Backend;
	
	use App\Http\Controllers\Controller;
	use App\Http\Datatables\ChannelVideoDatatable;
	use App\Http\Services\UploadService;
	use App\Models\Channel;
	use App\Models\Event;
	use App\Models\Tag;
	use App\Models\Video;
	use Illuminate\Http\Request;
	use Illuminate\Support\Str;
	use Log;
	
	class ChannelVideoController extends Controller
		{
		/**
		 * Display a listing of the resource.
		 */
			public function index ($channel)
				{
					$this->data['channel'] = Channel::whereIdentifier ($channel)->firstOrFail ();
					return view ('Backend.modules.channel_videos.index', $this->data);
				}
		
		/**
		 * Show the form for creating a new resource.
		 */
			public function create ($channelId)
				{
					$this->data['channel'] = Channel::findOrFail ($channelId);
					$this->data['events']  = Event::all ();
					
					return view ('Backend.modules.channel_videos.add', $this->data);
				}
			
			public function store (Request $request, $channelId)
				{
					$validatedData = $request->validate ([
						                                     'title'       => 'required|string|max:255',
						                                     'description' => 'required|string',
						                                     'event_id'    => 'required|exists:events,id',
						                                     'thumbnail'   => 'nullable|image|max:5120',
						                                     'video_path'  => 'required|file|mimetypes:video/avi,video/mpeg,video/mp4|max:0',
						                                     'tags'        => 'nullable|array',
						                                     'tags.*'      => 'nullable|string|max:50',
					                                     ]
					);
					
					$channel = Channel::findOrFail ($channelId);
					$event   = Event::findOrFail ($validatedData['event_id']);
					
					$channellId = $channel->identifier;
					
					$video              = new Video();
					$video->channel_id  = $channel->id;
					$video->event_id    = $event->id;
					$video->slug        = Str::slug ($validatedData['title']);
					$video->title       = $validatedData['title'];
					$video->description = $validatedData['description'];
					
					// Handle thumbnail upload
					if ($request->hasFile ('thumbnail'))
						{
							$uploadService    = new UploadService();
							$thumbnailPath    = $uploadService->file_upload ($request, 'thumbnail', 'thumbnails',
							                                                 'public_2'
							);
							$video->thumbnail = $thumbnailPath['path'];
						}
					
					// Handle video upload
					if ($request->hasFile ('video_path'))
						{
							$uploadService     = new UploadService();
							$videoPath         = $uploadService->file_upload ($request, 'video_path', 'videos',
							                                                  'public_2'
							);
							$video->video_path = $videoPath['path'];
						}
					
					$video->system_user_id = $request->user ('admin')->id;
					$result                = $video->save ();
					
					// Save tags to the tags table and attach tag IDs to the tags column of the videos table
					if ($result && isset($validatedData['tags']))
						{
							$tags = [];
							foreach ($validatedData['tags'] as $tagName)
								{
									$tag    = Tag::firstOrCreate (['name' => $tagName], ['slug' => Str::slug ($tagName)]
									);
									$tags[] = $tag->id;
								}
							$video->tags = json_encode ($tags); // Save tag IDs as a JSON array in the tags column
							$video->save ();
						}
					
					if ($result)
						{
							return self::success ('Channel videos', 'Video saved successfully.',
							                      route ('channel.video.index', ['channel' => $channellId])
							);
						}
					
					return self::fail ('Channel videos', 'Video not saved.',
					                   route ('channel.video.index', ['channel' => $channellId])
					);
				}
		
		
		/**
		 * Display the specified resource.
		 */
			public function show (string $id)
				{
					//
				}
		
		/**
		 * Show the form for editing the specified resource.
		 */
			public function edit ($channel, $id)
				{
					$this->data['video']   = Video::with ('tags')->findOrFail ($id);
					$this->data['tags']    = Tag::all ();
					$this->data['events']  = Event::all ();
					$this->data['channel'] = Channel::whereIdentifier ($channel)->firstOrFail ();
					
					
					$tagIdsString = $this->data['video']->tags;
					$tagIdsArray  = json_decode ($tagIdsString, true); // Convert the JSON string to an array
					
					$this->data['selectedTagIds'] = Tag::whereIn ('id', $tagIdsArray ?? [])->pluck ('id')->toArray ();
					
					
					return view ('Backend.modules.channel_videos.edit', $this->data);
				}
		
		/**
		 * Update the specified resource in storage.
		 */
			public function update (Request $request, $channelIdentifier, $id)
				{
					// Log the incoming request data
					Log::info ('Update request received', ['request' => $request->all ()]);
					
					// Validate the incoming request
					$validatedData = $request->validate ([
						                                     'title'       => 'required|string|max:255',
						                                     'description' => 'required|string',
						                                     'event_id'    => 'required|exists:events,id',
						                                     'thumbnail'   => 'nullable|image|max:5120',
						                                     'video_path'  => 'nullable|file|mimetypes:video/avi,video/mpeg,video/mp4|max:50000',
						                                     'tags'        => 'nullable|array',
						                                     'tags.*'      => 'nullable|string|max:50',
					                                     ]
					);
					
					// Find the video by ID
					$video = Video::findOrFail ($id);
					if (!$video)
						{
							Log::error ('Video not found', ['id' => $id]);
							return self::fail ('Channel videos', 'Video not found.',
							                   route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
					
					// Update video details
					$video->title       = $validatedData['title'];
					$video->description = $validatedData['description'];
					$video->event_id    = $validatedData['event_id'];
					
					// Handle thumbnail upload
					if ($request->hasFile ('thumbnail'))
						{
							$uploadService   = new UploadService();
							$thumbnailUpload = $uploadService->file_upload ($request, 'thumbnail', 'video_thumbnail',
							                                                'public_2'
							);
							if ($thumbnailUpload)
								{
									$video->thumbnail = $thumbnailUpload['path'];
								}
							else
								{
									Log::error ('Thumbnail upload failed');
								}
						}
					
					// Handle video upload
					if ($request->hasFile ('video_path'))
						{
							$uploadService = new UploadService();
							$videoUpload   = $uploadService->file_upload ($request, 'video_path', 'videos', 'public_2');
							if ($videoUpload)
								{
									$video->video_path = $videoUpload['path'];
								}
							else
								{
									Log::error ('Video upload failed');
								}
						}
					
					// Update tags
					if (isset($validatedData['tags']))
						{
							$tags = [];
							foreach ($validatedData['tags'] as $tagName)
								{
									$tag    = Tag::firstOrCreate (['name' => $tagName], ['slug' => Str::slug ($tagName)]
									);
									$tags[] = $tag->id;
								}
							$video->tags ()->sync ($tags);
						}
					
					
					// Save the video
					$result = $video->save ();
					if ($result)
						{
							return self::success ('Channel videos', 'Video updated successfully.',
							                      url ('/admin/backend/channel/'.$channelIdentifier.'/video')
							);
						}
					else
						{
							Log::error ('Video update failed', ['video' => $video]);
							return self::fail ('Channel videos', 'Video not updated.',
							                   route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
				}
		
		
		/**
		 * Remove the specified resource from storage.
		 */
			public function destroy ($channelIdentifier, $id)
				{
					// Find the video by ID
					$video = Video::findOrFail ($id);
					if (!$video)
						{
							Log::error ('Video not found', ['id' => $id]);
							return self::fail ('Channel videos', 'Video not found.',
							                   route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
					
					// Find the channel by identifier
					$channel = Channel::whereIdentifier ($channelIdentifier)->firstOrFail ();
					if (!$channel)
						{
							Log::error ('Channel not found', ['channel' => $channelIdentifier]);
							return self::fail ('Channel videos', 'Channel not found.',
							                   route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
					
					// Delete the video
					$result = $video->delete ();
					if ($result)
						{
							return self::success ('Channel videos', 'Video deleted successfully.',
							                      route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
					else
						{
							Log::error ('Video deletion failed', ['video' => $video]);
							return self::fail ('Channel videos', 'Video not deleted.',
							                   route ('channel.video.index', ['channel' => $channelIdentifier])
							);
						}
				}
		
		
		/**
		 * Custom method added for datatable.
		 *
		 * @return \Illuminate\Http\JsonResponse
		 */
			public function datatable (int $channel, Request $request, ChannelVideoDatatable $datatable)
				{
					$datatable->columns = [
						0 => 'id',
						1 => 'title',
						2 => 'description',
						6 => 'created_at'
					];
					return response ()->json ($datatable->data ($request, $channel));
				}
		}
