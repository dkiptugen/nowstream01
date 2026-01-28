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
					$this->data['channels']      = $this->get_channels ();
					$this->data['streams']       = $this->get_streams (null, 6);
					$this->data['events']        = $this->get_events ();
					$this->data['videos']        =  $this->get_videos ();
					$this->data['current_event'] = Stream::take (1)->orderBy ("created_at", "asc")->get ();

					return view ('Frontend.index', $this->data);
				}

			public function landing ()
				{
					$this->data['channels'] =  $this->get_channels ();
					$this->data['streams'] =$this->get_streams ();
					$this->data['events']  =  $this->get_events ();
					$this->data['videos']  = $this->get_videos ();

					$this->data['current_event'] = $this->get_events (1);

					if ($this->data['current_event'])
						{
							$this->data['rates'] = $this->get_event_rates ($this->data['current_event']->id);
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
