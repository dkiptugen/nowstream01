<?php

	namespace App\Http\Controllers\Backend;

	use App\Http\Controllers\Controller;
	use App\Http\Datatables\EventDatatable;
	use App\Http\Requests\StoreEvent;
	use App\Http\Requests\UpdateEvent;
	use App\Http\Services\UploadService;
	use App\Models\Channel;
	use App\Models\Event;
	use App\Models\Stream;
    use App\Traits\Meta;
    use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Str;

	class EventController extends Controller
		{
            use Meta;
		/**
		 * Display a listing of the resource.
		 */
			public function index ($channelId)
				{
					$this->data['channel'] = $channelId;
					$this->data['title']   = 'Events : '.$this->data['title'];
					return view ('Backend.modules.event.index', $this->data);
				}

		/**
		 * Show the form for creating a new resource.
		 */
			public function create ($channelId)
				{
					$this->data['channel'] = Channel::whereIdentifier ($channelId)->first ()
					;
					$this->data['title']   = 'Events : '.$this->data['title'];
					return view ('Backend.modules.event.add', $this->data);
				}

		/**
		 * Store a newly created resource in storage.
		 */
			public function store ($channelId, StoreEvent $request)
				{
					try
						{

							[$startDateString, $endDateString] = explode (' - ', $request->event_time);


							$startDate = Carbon::createFromFormat ('Y/m/d h:i A', $startDateString);
							$endDate   = Carbon::createFromFormat ('Y/m/d h:i A', $endDateString);
							$channel   = Channel::whereIdentifier ($channelId)->first ()
							;
							$validated = $request->validated ();
							if ($validated)
								{
									$event               = new Event();
									$event->event_name   = $request->event_name;
									$event->description  = $request->event_description;
									$event->publish_date = $request->publishdate;
									$event->start_time   = $startDate;
									$event->end_time     = $endDate;
									$event->venue        = $request->venue;
									if ($request->hasFile ('thumbnail'))
										{
											$image              = new UploadService();
											$upload             = $image->file_upload ($request, 'thumbnail',
												'event_image', 'linode');
											$event->event_image = $upload['path'];

										}

									$event->system_user_id = $request->user ('admin')->id;
									$event->status         = 1;
									$event->channel_id     = $channel->id;
									$res                   = $event->save ();
									if ($res)
										{
											$streamkey           = Str::ulid ();
											$stream              = new Stream();
											$stream->identifier  = self::identifer ('Stream', 'identifier');
											$stream->title       = $event->event_name;
											$stream->description = $request->event_description;
											if ($request->hasFile ('thumbnail'))
												{
													$image                 = new UploadService();
													$upload                = $image->file_upload ($request,
														'stream_thumbnail', 'stream_thumbnail', 'public_2');
													$stream->thumbnail_url = $upload['path'];

												}

											$stream->stream_key        = $streamkey;
											$stream->stream_url        = config ('custom.STREAM.LIVESTREAM_SERVER');
											$stream->stream_video_link = config ('custom.STREAM.LIVESTREAM_LINK').'/'.$streamkey.'.m3u8';
											$stream->start_time        = $startDate;
											$stream->event_id          = $event->id;
											$stream->system_user_id    = $request->user ('admin')->id;
											$stream->channel_id        = $channel->id;
											$stream->save ();
											return self::success ('event', 'Saved successfully',
												route ('channel.event.index', $channelId));
										}
									return self::failed ('event', 'error encountered when saving, try again later',
										route ('channel.event.index', $channelId));
								}
							else
								{
									return self::fail ('event', $validated, route ('channel.event.index', $channelId));
								}
						}
					catch (\Exception $e)
						{
							Log::error ($e->getMessage ().$e->getLine ().$e->getTraceAsString ());
						}
				}

		/**
		 * Display the specified resource.
		 */
			public function show ($channelId, Event $event)
				{
					//
				}

		/**
		 * Show the form for editing the specified resource.
		 */
			public function edit ($channelId, int $id)
				{
					$this->data['channel'] = Channel::whereIdentifier ($channelId)->first ()
					;
					$this->data['event']   = Event::find ($id);
					$this->data['title']   = $this->data['event']->title.' Event : '.$this->data['title'];
					return view ('Backend.modules.event.edit', $this->data);
				}

		/**
		 * Update the specified resource in storage.
		 */
			public function update ($channelId, UpdateEvent $request, $id)
				{
					try
						{
							[$startDateString, $endDateString] = explode (' - ', $request->event_time);


							$startDate = Carbon::createFromFormat ('Y/m/d h:i A', $startDateString);
							$endDate   = Carbon::createFromFormat ('Y/m/d h:i A', $endDateString);
							$channel   = Channel::whereIdentifier ($channelId)->first ()
							;
							$validated = $request->validated ();
							if ($validated)
								{
									$event               = Event::find ($id);
									$event->event_name   = $request->event_name;
									$event->description  = $request->event_description;
									$event->publish_date = $request->publishdate;
									$event->start_time   = $startDate;
									$event->end_time     = $endDate;
									$event->venue        = $request->venue;
									if ($request->hasFile ('thumbnail'))
										{
											$image              = new UploadService();
											$upload             = $image->file_upload ($request, 'thumbnail',
												'event_image', 'linode');
											$event->event_image = $upload['path'];

										}

									$event->system_user_id = $request->user ('admin')->id;
									$event->status         = 1;
									$event->channel_id     = $channel->id;
									$res                   = $event->save ();
									if ($res)
										{

											$stream = $event->streams;

											$stream->title       = $event->event_name;
											$stream->description = $event->description;
											if ($request->hasFile ('thumbnail'))
												{
													$image                 = new UploadService();
													$upload                = $image->file_upload ($request,
														'stream_thumbnail', 'stream_thumbnail', 'public_2');
													$stream->thumbnail_url = $upload['path'];

												}
											$stream->start_time     = $startDate;
											$stream->system_user_id = $request->user ('admin')->id;
											$stream->channel_id     = $channel->id;
											$stream->save ();
											return self::success ('event', 'Saved successfully',
												route ('channel.event.index', $channelId));
										}
									return self::fail ('event', 'error encountered when saving, try again later',
										route ('channel.event.index', $channelId));
								}
							else
								{
									return self::fail ('event', $validated, route ('channel.event.index', $channelId));
								}
						}
					catch (\Exception $e)
						{
							Log::error ($e->getMessage ());
						}
				}

		/**
		 * Remove the specified resource from storage.
		 */
			public function destroy ($channelId, $id) {}

			public function datatable ($channelId, Request $request, EventDatatable $datatable)
				{
					$channel            = Channel::whereIdentifier ($channelId)->first ();

					$datatable->columns = [
						1 => 'event_name', 2 => 'thumbnail', 7 => 'created_at', 6 => 'status', 8 => 'publish_date'
					];
					return response ()->json ($datatable->data ($request, $channel->id));
				}
		}
