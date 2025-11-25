<?php
	
	namespace App\Http\Controllers\Frontend;
	
	use App\Models\EventRate;
	
	use App\Http\Controllers\Controller;
	use App\Models\Channel;
	use App\Models\Event;
	use App\Models\Stream;
	use App\Models\Video;
	use App\Traits\CacheHelper;
	use Illuminate\Support\Facades\Cache;
	
	class HomeController extends Controller
		{
			use CacheHelper;
		
		/**
		 * Display a listing of the resource.
		 */
			public function index ()
				{
					$this->data['channels']      = Cache::rememberOnce ('channels', now ()->addDay (), $this->get_channels ());
					$this->data['streams']       = Cache::rememberOnce ('streams_not_6', now ()->addDay (), $this->get_streams (null, 6));
					$this->data['events']        = Cache::rememberOnce ('events', now ()->addDay (), $this->get_events ());
					$this->data['videos']        = Cache::rememberOnce ('videos', now ()->addDay (), $this->get_videos ());
					$this->data['current_event'] = Stream::take (1)->orderBy ("created_at", "asc")->get ();
					
					return view ('Frontend.index', $this->data);
				}
			
			public function landing ()
				{
					$this->data['channels'] = Cache::rememberOnce ('channels', now ()->addDay (), $this->get_channels ());;
					$this->data['streams'] = Cache::rememberOnce ('streams', now ()->addDay (), $this->get_streams ());
					$this->data['events']  = Cache::rememberOnce ('events', now ()->addDay (), $this->get_events ());
					$this->data['videos']  = Cache::rememberOnce ('videos', now ()->addDay (), $this->get_videos ());
					
					$this->data['current_event'] = Cache::rememberOnce ('event_1', now ()->addDay (),
					                                                    $this->get_events (1));;
					
					if ($this->data['current_event'])
						{
							$this->data['rates'] = Cache::rememberOnce ('rates_'.$this->data['current_event']->id,
							                                            now ()->addDay (),
							                                            $this->get_event_rates ($this->data['current_event']->id));
						}
					else
						{
							$this->data['rates'] = collect ();
						}
					
					return view ('Frontend.landing', $this->data);
				}
			
			public function terms ()
				{
					return view ('Frontend.terms');
				}
			
			
		}
