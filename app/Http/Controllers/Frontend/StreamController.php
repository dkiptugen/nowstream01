<?php


namespace App\Http\Controllers\Frontend;

use App\Events\LogoutUser;
use App\Events\NewComment;
use App\Http\Controllers\Auth\User\AuthsController;
use App\Http\Controllers\Controller;
use App\Libs\AfricasTalking;
use App\Libs\SafaricomContent;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Event;
use App\Models\ContentRate;
use App\Models\Rate;
use App\Models\Content;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;
use App\Rules\ValidatePhone;
use App\Traits\Meta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class StreamController extends Controller
{
    use Meta;
	public function index()
	{
		$streams = Content::where('content_group', 'livestream')->with(['event', 'channel', 'rates'])
		->orderByDesc('created_at')
		->get();
		$topstreams = Content::where('content_group', 'livestream')->with(['event', 'channel', 'rates'])
		->orderByDesc('views')
		->get();

		$this->data['streams'] = $streams;
		$this->data['topstreams'] = $topstreams;
		return view('Frontend.modules.channels.streams.index', $this->data);
	}


	public function proxyStream(int $streamId)
	{
		$stream = Content::find($streamId);
		if (!$stream) {
			abort(404);

		}

		$url = $stream->stream_video_link;

		try {
			$response = Http::withHeaders([
				'Accept' => 'application/vnd.apple.mpegurl, application/x-mpegURL, application/octet-stream',
				'Range' => request()->header('Range') // Forward range requests
			])->get($url);

			if ($response->successful()) {
				$contentType = $response->header('Content-Type');

				// Handle common HLS MIME types
				if (str_contains($url, '.m3u8')) {
					$contentType = 'application/vnd.apple.mpegurl';
				} elseif (str_contains($url, '.ts')) {
					$contentType = 'video/mp2t';
				}

				$headers = [
					'Content-Type' => $contentType,
					'Cache-Control' => 'no-cache, no-store, must-revalidate',
					'Accept-Ranges' => 'bytes',
					'Connection' => 'keep-alive',
				];

				// Handle Content-Range header if present
				if ($response->header('Content-Range')) {
					$headers['Content-Range'] = $response->header('Content-Range');
				}

				return response($response->body(), $response->status())->withHeaders($headers);
			} else {
				return response('Error fetching HLS stream', 500);
			}
		} catch (Exception $e) {
			return response('Error fetching HLS stream', 500);
		}
	}


	public function validatePhoneNumber(
		$phoneNumber
	): bool {
		$validate = new ValidatePhone();
		return $validate->passes('phone', $phoneNumber);
	}

	public function postComment(Request $request, $commentableType, $commentableId)
	{
		$user = Auth::user();

		$comment = new Comment();
		$comment->user_id = $user->id;
		$comment->commentable_type = 'App\\Models\\' . ucfirst($commentableType);
		$comment->commentable_id = $commentableId;
		$comment->comment = $request->input('comment');
		$comment->save();

		if ($request->ajax()) {
			event(new NewComment($comment));
			return response()->json([
				'success' => true,
				'comment' => $comment->comment,
				'user_name' => $user->name,
				'user_image' => $user->image ? asset('storage/' . $user->image) : asset('assets/images/avatars/avatar-2.png')
			]);
		}

		return redirect()->back()->with('success', 'Comment posted successfully!');
	}

	// public function findStream(Request $request)
	// 	{
	// 		$streamKey = $request->input('stream_token');
	// 		if ($this->validatePhoneNumber($streamKey))
	// 			{
	// 				return redirect()->back()->with('error', 'This is a phone number');
	// 			}
	// 		else
	// 			{
	// 				// Check if the stream key exists in the subscriptions table
	// 				$subscription = Subscription::where('stream_token', $streamKey)->first();

	// 				if ($subscription)
	// 					{
	// 						// Get the event ID from the subscription
	// 						$eventId = $subscription->event_id;
	// 						if(!Auth::check ())
	// 							{
	// 								Auth::loginUsingId($subscription->user_id);
	// 							}
	// 						// Find the corresponding stream
	// 						$stream = Content::where('event_id', $eventId)->first();
	// 						if ($subscription->status == 1)
	// 							{
	// 								if ($stream)
	// 									{
	// 										// Redirect to the stream page
	// 										return redirect()->route('stream.show', ['streamId' => $stream->id, 'slug' => $stream->slug]);
	// 									}
	// 								else
	// 									{
	// 										return redirect()->back()->with('error', 'Content not found for the given event ID.');
	// 									}
	// 							}
	// 						else
	// 							{
	// 								$event = Event::find($eventId);
	// 								return redirect()->route('event.pay', [$eventId, $subscription->event_rate_id]);
	// 							}

	// 					}
	// 				else
	// 					{
	// 						return redirect()->back()->with('error', 'Invalid stream key.');
	// 					}
	// 			}

	// 	}
	public function phone_auth($phone)
	{


		$phone = $this->removeSpaces("254" . substr($phone, -9));

		$user = User::where('phone', $phone)->first();

		if (is_null($user)) {
			// User does not exist, create a new account
			$lastFourDigits = substr($phone, -4);
			$username = Str::random(6) . $lastFourDigits;
			$email = $username . '@live.baze.co.ke';

			$user = User::create([
				'name' => $username,
				'email' => $email,
				'phone' => $phone,
				'password' => Hash::make($phone),
			]);
			$at = new AfricasTalking();
			$at->send_sms(
				'baze',
				$this->removeSpaces('0' . substr($phone, -9)),
				'Welcome to Live.baze.co.ke . Kindly update your profile to enjoy a better experience.'
			);
		}
		Auth::loginUsingId($user->id);
		if (!is_null($user->stream_auth) && $user->stream_auth !== session()->getId()) {
			// Delete the previous session from the database
			DB::table('sessions')->where('user_id', $user->id)->delete();
		}

		Session::regenerate();
		$user->stream_auth = session()->getId();
		$user->verification_key = null;
		$user->save();
		event(LogoutUser::broadcast($user->id));
		return $user;
	}

	public function check_saf_content($user, $eventID)
	{
		$safContent = new SafaricomContent();
		$check = $safContent->query_active_subscription(substr($user->phone, -9));

		if ($check->status && !empty($check->data->body)) {


			foreach ($check->data->body as $data) {
				$def = $safContent->products($data->ProductId);

				$rate = ContentRate::updateOrCreate([
					'event_id' => $eventID,
					'name' => $data->ProductId,
					'cost' => $data->ProductPrice,

				], [
					'has_stream' => $def->stream,
					'has_video' => $def->video,
					'reserved_currency' => config('custom.BILLING.RESERVED_CURRENCY'),
					'reserved_currency_cost' => $def->reserved_currency_cost,
					'visible' => 0,
					'status' => 1
				]);


				Subscription::updateOrCreate(
					[
						'user_id' => $user->id,

						'event_id' => $eventID,
						'event_rate_id' => $rate->id,
						'channel_id' => 3,
					],
					[

						'identifier' => $this->identifer('Subscription', 'identifier'),
						'type' => 'stream',
						'currency' => 'KES',
						'cost' => $rate->cost,
						'amount_paid' => $rate->cost,
						'balance' => 0,
						'status' => 1,
						'stream_token' => uniqid(),
						'has_stream' => $def->stream,
						'has_video' => $def->video,
						'created_at' => Carbon::parse($data->SubscriptionDate)
					]
				);
			}


		}
		$checkSub = Subscription::where('user_id', $user->id)
			->where('event_id', $eventID)
			->where('channel_id', 3)
			->first();
		//dd($checkSub);

		$stream = Content::where('event_id', $eventID)->first();
		if (!is_null($checkSub)) {
			if ($checkSub->status == 1) {
				if ($stream) {
					return redirect()->route('stream.show', ['streamId' => $stream->id, 'slug' => $stream->slug]);
				}
			}
		}

		return false;

	}

	public function findStream(Request $request)
	{
		$streamKey = $request->input('stream_token');
		$eventID = $request->input('event_id');


		// Check if the input is a phone number
		if ($this->validatePhoneNumber($streamKey)) {

			//$request->merge(['phone' => $streamKey]);
			if (!Auth::check() || substr(Auth::user()->phone,-9) != substr($streamKey,-9)) {
				$this->phone_auth($streamKey);
			}
			$user = Auth::user();

			if (!$user) {
				return redirect()->back()->with('error', 'Phone authentication failed.');
			}

			$check = $this->check_saf_content($user, $eventID);
			if ($check) {
				return $this->checkSubscriptionAndRedirect($streamKey, $eventID);
			}
		}

		// If not a phone number, check for subscription with the stream key
		return $this->checkSubscriptionAndRedirect($streamKey, $eventID);
	}

	private function checkSubscriptionAndRedirect($streamKey, $eventID)
	{
		$subscription = Subscription::where(function ($query) use ($streamKey) {
			return $query->where('user_id', Auth::id())
				->orWhere('stream_token', $streamKey);
		})
			->where('event_id', $eventID)
			->first()
		;

		if ($subscription) {
			if (!Auth::check()) {
				Auth::loginUsingId($subscription->user_id);
			}
			$stream = Content::where('event_id', $subscription->event_id)->first();

			if ($subscription->status == 1) {
				if ($stream) {
					return redirect()->route('stream.show', ['streamId' => $stream->id, 'slug' => $stream->slug]);
				} else {
					return redirect()->back()->with('error', 'Content not found for the given event ID.');
				}
			} else {
				return redirect()->route('event.pay', [$subscription->event_id, $subscription->event_rate_id]);
			}
		} else {
			$event = Event::find($eventID);

			if (!$event) {
				return redirect()->route('events')->with('error', 'Event not found.');
			}

			return redirect()->route('event.show', [$eventID, $event->slug]);
		}
	}


	/**
	 * Display the specified resource.
	 */

	public function freeShow($slug = "")
{
    try { 
				$uuid = Content::where('content_group', 'livestream')->where('slug', $slug)->value('uuid');

        $stream = Cache::rememberOnce('stream_'.$uuid,now()->addDay(),Content::where('uuid', $uuid)->firstOrFail());
 
        $user = Auth::user();

         if ($user) {
            $uniqueViewKey = "stream_view_{$stream->uuid}_{$user->id}";

             if (!Cache::has($uniqueViewKey)) {
                // Increment the viewer count
                $stream->increment('viewers');

                 Cache::put($uniqueViewKey, true, 3600);

                 $stream->watch()
                    ->updateOrCreate(
                        [
                            'user_id' => $user->id,
                        ],
                        [
                            'watched_at' => now(),
                        ]
                    );
            }
        } else { 
            $stream->increment('viewers');
            $stream->increment('views');
        }
 
        $streams = Content::where('status', 1)->where('uuid', '<>', $stream->uuid)->take(4)->get();
        $channels = Channel::where('status', 1)->take(8)->get();
        $videos = Content::where('type', 'video')->take(12)->get();
        $comments = $stream->comments()->with('user')->get();
		//dd($stream); 
        $data = [
            'stream' => $stream,
            'streams' => $streams,
            'channels' => $channels,
            'videos' => $videos,
            'comments' => $comments
        ]; 

        return view('Frontend.modules.channels.streams.stream', $data);
    } catch (Exception $e) {
        // Log the exception for debugging
        Log::error('Content not found: ' . $e->getMessage());

        // Return a 404 error
        abort(404, 'Content not found');
    }
}

	public function show($uuid, $slug = "")
{
    try { 
        $stream = Content::where('uuid', $uuid)->firstOrFail();

        $user = Auth::user();
 
        if ($user && $stream->event_id) {

            $subscription = Subscription::where('user_id', $user->id)
                ->where('event_id', $stream->event_id)
                ->where('status', 1)
                ->first();

            if (!$subscription) {
                return redirect()->route('event.show', [
                    'eventId' => $stream->event_id,
                    'slug' => $stream->slug
                ]);
            }
        }

        // Viewer tracking
        if ($user) {
            $uniqueViewKey = "stream_view_{$stream->uuid}_{$user->id}";

            if (!Cache::has($uniqueViewKey)) {
                $stream->increment('viewers');
                Cache::put($uniqueViewKey, true, now()->addHour());

                $stream->watch()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['watched_at' => now()]
                );
            }
        } else {
            $stream->increment('viewers');
        }

        // Related data
        $streams = Content::where('status', 1)
            ->where('uuid', '<>', $uuid)
            ->take(4)
            ->get();

        $channels = Channel::where('status', 1)->take(8)->get();

        $videos = Content::where('type', 'video')
            ->where('status', 1)
            ->take(12)
            ->get();

        $comments = $stream->comments()
            ->with('user')
            ->latest()
            ->get();

        return view('Frontend.modules.channels.streams.stream', [
            'stream' => $stream,
            'streams' => $streams,
            'channels' => $channels,
            'videos' => $videos,
            'comments' => $comments
        ]);

    } catch (\Exception $e) {
        Log::error('Stream not found: ' . $e->getMessage());
        abort(404);
    }
}


}
