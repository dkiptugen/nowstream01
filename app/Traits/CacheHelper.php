<?php

	namespace App\Traits;

	use App\Models\Channel;
	use App\Models\Event;
	use App\Models\EventRate;
	use App\Models\Content;
	use App\Models\Video;

	trait CacheHelper
		{
			public function get_channels ($id = null)
				{
					if (is_null ($id))
						{
							$channels = Channel::with (['streams', 'videos'])->where ('status', 1)->orderBy ("id",
							                                                                                 "desc")->get ()
							;
						}
					else
						{
							$channels = Channel::with (['streams', 'videos'])->find ($id);
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
							$video = Video::get ();
						}
					else
						{
							$video = Video::find ($id);
						}
					return $video;
				}

			public function get_event_rates ($eventId, $eventRateId = null)
				{
					if (is_null ($eventRateId))
						{
							$rates = EventRate::where ('event_id', $eventId)->where ('visible', 1)->get ()
							;
						}
					else
						{
							$rates = EventRate::find ($eventRateId);
						}
					return $rates;
				}

			public function get_streams ($id = null, $not = 0)
				{
					if (is_null ($id))
						{
							$stream = Content::when ($not != 0, function ($query) use ($not)
								{
									return $query->where ('id', '!=', $not);
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
