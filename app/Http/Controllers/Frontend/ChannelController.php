<?php





	namespace App\Http\Controllers\Frontend;

	use App\Http\Controllers\Controller;
	use App\Models\Channel;
    use App\Models\Microsite;
    use App\Models\Video;
	use App\Models\Content;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Cache;
	use App\Traits\CacheHelper;

	class ChannelController extends Controller
		{
			use CacheHelper;
		/**
		 * Display a listing of the resource.
		 */
			public function index()
				{
					$this->data['channels'] =  Cache::rememberOnce('channels',now()->addDay(),$this->get_channels());
					return view('Frontend.modules.channels.index', $this->data);
				}

		/**
		 * Display the specified resource.
		 */


			public function show($id)
				{
					try
						{

							$this->data['channels'] = Cache::rememberOnce('channels',now()->addDay(),$this->get_channels());
							$this->data['channel']  = Cache::rememberOnce('channel_'.$id,now()->addDay(),$this->get_channels($id));;

							$this->data['videos']   = $this->data['channel']->videos;
							$this->data['streams']  = $this->data['channel']->streams;

							return view('Frontend.modules.channels.channel', $this->data);

						}
					catch (\Exception $e)
						{
							abort(404, 'Channel not found');
						}
				}

			public function subscribe(Request $request, $channelId)
				{
					$channel = Cache::rememberOnce('channel_'.$channelId,now()->addDay(),Microsite::findOrFail($channelId));
					$user    = Auth::user();

					if ($request->ajax())
						{
							$user->subscribedChannels()->syncWithoutDetaching($channelId);
							$subscriberCount = $channel->subscribers()->count();

							return response()->json(['success' => 'Subscribed successfully!', 'subscriber_count' => $subscriberCount]);
						}

					$user->subscribedChannels()->syncWithoutDetaching($channelId);

					return redirect()->back()->with('success', 'Subscribed successfully!');
				}

			public function unsubscribe(Request $request, $channelId)
				{
					$channel = Cache::rememberOnce('channel_'.$channelId,now()->addDay(),Microsite::findOrFail($channelId));
					$user    = Auth::user();

					if ($request->ajax())
						{
							$user->subscribedChannels()->detach($channelId);
							$subscriberCount = $channel->subscribers()->count();

							return response()->json(['success' => 'Unsubscribed successfully!', 'subscriber_count' => $subscriberCount]);
						}

					$user->subscribedChannels()->detach($channelId);

					return redirect()->back()->with('success', 'Unsubscribed successfully!');
				}

		}
