<?php

	namespace App\Traits;

	use App\Models\Channel;
	use App\Models\Event;
	use App\Models\ContentRate;
	use App\Models\Content;
	use App\Models\Video;

	trait CacheHelper
		{
			public function get_channels ($id = null)
				{
					if (is_null ($id))
						{
							$channels = Channel::with (['streams', 'contents'])->where ('status', 1)->orderBy ("created_at",
							                                                                                 "desc")->get ()
							;
						}
					else
						{
							$channels = Channel::with (['streams', 'contents'])->find ($id);
						}

					return $channels;
				}

			public function get_events ($id = null, $not = 0)
				{
					if (is_null ($id))
						{
							$events = Event::where ('status', 1)->when ($not != 0, function ($query) use ($not)
									{
										return $query->where ('id', '<>', $not);
									})->get ()
							;
						}
					else
						{
							$events = Event::find ($id);
						}
					return $events;
				}

			public function get_videos ($id = null)
				{
					if (is_null ($id))
						{
							$video = Content::where('type', 'video')->get ();
						}
					else
						{
							$video = Content::where('type', 'video')->find ($id);
						}
					return $video;
				}

			public function get_event_rates ($eventId, $eventRateId = null)
				{
					if (is_null ($eventRateId))
						{
							$rates = ContentRate::where ('event_id', $eventId)->where ('visible', 1)->get ()
							;
						}
					else
						{
							$rates = ContentRate::find ($eventRateId);
						}
					return $rates;
				}

			public function get_streams ($id = null, $not = 0)
				{
					if (is_null ($id))
						{
							$stream = Content::when ($not != 0, function ($query) use ($not)
								{
									return $query->where ('uuid', '!=', $not);
								})->orderBy ("created_at", "asc")->get ()
							;
						}
					else
						{
							$stream = Content::find ($id);
						}
					return $stream;
				}
		}
