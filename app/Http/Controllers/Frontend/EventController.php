<?php

	namespace App\Http\Controllers\Frontend;

	use App\Models\Event;
	use App\Models\ContentRate;
	use App\Models\Region;
	use App\Models\Subscription;
	use App\Models\Video;
	use App\Traits\CacheHelper;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Cache;
	use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;
	use Stevebauman\Location\Facades\Location;

	class EventController extends Controller
		{
			use CacheHelper;
		/**
		 * Display a listing of the resource.
		 */
			public function index()
				{


					// Add the fetched events to the data array
					$events = Event::with(['eventRates' => function($q){
						$q->where('status', 1)->orderBy('cost', 'asc');
					}])->where('status', 1)->get();

					return view('Frontend.modules.events.index', compact('events'));
				}

			public function pay(Request $request, $id, $rate_id)
				{
					//dd($request->country);
					try
						{

							$this->data['event']= Cache::rememberOnce('event_'.$id,now()->addDay(),$this->get_events ($id));
							$this->data['rate'] = Cache::rememberOnce('rates_'.$id.'_'.$rate_id,now()->addDay (), $this->get_event_rates ($id,$rate_id));;

							if (is_null ($this->data['rate']))
								{
									return redirect()->back()->with('error', 'Event rate not found');
								}

							$this->data['user']  =  Auth::user();

							$this->data['events'] = Cache::rememberOnce('events',now()->addDay(),$this->get_events ());

							$this->data['videos'] = Cache::rememberOnce('videos',now()->addDay (),$this->get_videos ());

							return view('Frontend.modules.payments.plans', $this->data);

						}
					catch (\Exception $e)
						{
							abort(404, 'Event not found');
						}
				}

			public function mpesa()
				{
					try
						{
							$this->data['user'] = Auth::user();;
							return view('Frontend.modules.payments.mpesa', $this->data);
						}
					catch (\Exception $e)
						{
							abort(404, 'Event not found');
						}
				}


			public function succeed($eventId)
				{
					$this->data['events'] =Cache::rememberOnce('event_'.$eventId,now()->addDay(),$this->get_events ($eventId));
					return view('Frontend.modules.payments.success', $this->data);
				}

		/**
		 * Display the specified resource.
		 */

			public function show($eventId)
				{
					try
						{

							$this->data['event']  = Cache::rememberOnce('event_'.$eventId,now()->addDay(),$this->get_events ($eventId));;
							$this->data['events'] = Cache::rememberOnce('event_not_'.$eventId,now()->addDay(),$this->get_events (null,$eventId));;
							$this->data['rates']  = Cache::rememberOnce('rates_'.$eventId,now()->addDay (),$this->get_event_rates ($eventId));
							$this->data['videos'] = Cache::rememberOnce('videos',now()->addDay (),$this->get_videos ());

							return view('Frontend.modules.events.event', $this->data);

						}
					catch (\Exception $e)
						{
							abort(404, 'event not found');
						}
				}
		}
