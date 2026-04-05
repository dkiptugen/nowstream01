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
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\URL;
	use Illuminate\Support\Str;
	use App\Models\Order;


class StreamController extends Controller
{
    use Meta;

    public function proxy_stream(string $streamId)
    {
        return $this->proxyStream($streamId);
    }

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


	public function proxyStream(string $streamId)
	{
		$stream = $this->cachedStream($streamId);
		$masterUrl = $this->resolveProxySourceUrl($stream);
		$rangeHeader = (string) request()->header('Range', '');

		try {
			if ($rangeHeader === '') {
				$cachedPlaylist = $this->getCachedPlaylistResponse($this->playlistCacheKey($streamId, $masterUrl));
				if ($cachedPlaylist !== null) {
					return $cachedPlaylist;
				}
			}

			$response = Http::withOptions(['stream' => true, 'timeout' => 30])
				->withHeaders([
					'Accept' => 'application/vnd.apple.mpegurl, application/x-mpegURL, application/octet-stream',
					'Range' => $rangeHeader,
				])
				->get($masterUrl);

			if (!$response->successful() && $response->status() !== 206) {
				return response('Error fetching stream playlist', 502);
			}

			$contentType = strtolower((string) $response->header('Content-Type'));
			$isPlaylist = str_contains($masterUrl, '.m3u8') || str_contains($contentType, 'mpegurl');

			if ($isPlaylist) {
				$rewritten = $this->rewritePlaylistUrls((string) $response->body(), $masterUrl, $streamId);

				return $this->cachePlaylistResponse(
					$this->playlistCacheKey($streamId, $masterUrl),
					$rewritten,
					'application/vnd.apple.mpegurl',
					$rangeHeader === ''
				);
			}

			return $this->streamUpstreamResponse($response, $masterUrl);
		} catch (Exception $e) {
			Log::error('Stream proxy failed: ' . $e->getMessage());
			return response('Error fetching stream', 502);
		}
	}

	public function proxyAsset(Request $request, string $streamId)
	{
		$encoded = (string) $request->query('src', '');
		if ($encoded === '') {
			abort(400, 'Missing src parameter');
		}

		$assetUrl = base64_decode($encoded, true);
		if ($assetUrl === false || !filter_var($assetUrl, FILTER_VALIDATE_URL)) {
			abort(400, 'Invalid src parameter');
		}

		$stream = $this->cachedStream($streamId);
		$originUrl = $this->resolveProxySourceUrl($stream);
		$originHost = parse_url($originUrl, PHP_URL_HOST);
		$assetHost = parse_url($assetUrl, PHP_URL_HOST);

		if (!$originHost || !$assetHost || !hash_equals((string) $originHost, (string) $assetHost)) {
			abort(403, 'Asset host not allowed');
		}

		$assetUrl = $this->normalizeUpstreamUrl($assetUrl);
		$rangeHeader = (string) $request->header('Range', '');

		try {
			if ($rangeHeader === '') {
				$cachedPlaylist = $this->getCachedPlaylistResponse($this->playlistCacheKey($streamId, $assetUrl));
				if ($cachedPlaylist !== null) {
					return $cachedPlaylist;
				}
			}

			$response = Http::withOptions(['stream' => true, 'timeout' => 30])
				->withHeaders([
					'Range' => $rangeHeader,
					'Accept' => 'application/vnd.apple.mpegurl, application/x-mpegURL, video/mp2t, application/octet-stream',
				])
				->get($assetUrl);

			if (!$response->successful() && $response->status() !== 206) {
				return response('Error fetching stream asset', 502);
			}

			$contentType = strtolower((string) $response->header('Content-Type'));
			$isPlaylist = str_contains($assetUrl, '.m3u8') || str_contains($contentType, 'mpegurl');

			if ($isPlaylist) {
				$rewritten = $this->rewritePlaylistUrls((string) $response->body(), $assetUrl, $streamId);
				return $this->cachePlaylistResponse(
					$this->playlistCacheKey($streamId, $assetUrl),
					$rewritten,
					'application/vnd.apple.mpegurl',
					$rangeHeader === ''
				);
			}

			return $this->streamUpstreamResponse($response, $assetUrl);
		} catch (Exception $e) {
			Log::error('Stream asset proxy failed: ' . $e->getMessage());
			return response('Error fetching stream asset', 502);
		}
	}

	private function rewritePlaylistUrls(string $playlist, string $baseUrl, string $streamId): string
	{
		$lines = preg_split('/\r\n|\r|\n/', $playlist) ?: [];
		$rewritten = [];

		foreach ($lines as $line) {
			$trimmed = trim($line);

			if ($trimmed === '') {
				$rewritten[] = $line;
				continue;
			}

			if (str_starts_with($trimmed, '#')) {
				if (preg_match('/URI="([^"]+)"/', $line, $matches) === 1) {
					$absolute = $this->makeAbsoluteUrl($baseUrl, $matches[1]);
					if ($absolute) {
						$absolute = $this->normalizeUpstreamUrl($absolute);
						$proxyUrl = URL::temporarySignedRoute('stream.proxy.asset', now()->addMinutes($this->streamProxyTtlMinutes()), [
							'streamId' => $streamId,
							'src' => base64_encode($absolute),
						]);
						$line = str_replace($matches[1], $proxyUrl, $line);
					}
				}
				$rewritten[] = $line;
				continue;
			}

			$absolute = $this->makeAbsoluteUrl($baseUrl, $trimmed);
			if (!$absolute) {
				$rewritten[] = $line;
				continue;
			}

			$absolute = $this->normalizeUpstreamUrl($absolute);
			$rewritten[] = URL::temporarySignedRoute('stream.proxy.asset', now()->addMinutes($this->streamProxyTtlMinutes()), [
				'streamId' => $streamId,
				'src' => base64_encode($absolute),
			]);
		}

		return implode("\n", $rewritten);
	}

	private function makeAbsoluteUrl(string $baseUrl, string $resource): ?string
	{
		if (preg_match('/^https?:\/\//i', $resource)) {
			return $resource;
		}

		$base = parse_url($baseUrl);
		if (!$base || empty($base['host'])) {
			return null;
		}

		$scheme = $base['scheme'] ?? 'https';
		$host = $base['host'];
		$port = isset($base['port']) ? ':' . $base['port'] : '';

		if (str_starts_with($resource, '/')) {
			return $scheme . '://' . $host . $port . $resource;
		}

		$path = $base['path'] ?? '/';
		$dir = rtrim(str_replace('\\', '/', dirname($path)), '/');
		$dir = $dir === '' ? '' : $dir;

		return $scheme . '://' . $host . $port . $dir . '/' . ltrim($resource, '/');
	}

	private function normalizeUpstreamUrl(string $url): string
	{
		$parts = parse_url($url);
		if (!$parts || empty($parts['scheme']) || strtolower($parts['scheme']) !== 'http') {
			return $url;
		}

		$host = (string) ($parts['host'] ?? '');
		$port = (int) ($parts['port'] ?? 80);

		// Many streaming providers expose custom radio/TV ports over plain HTTP only.
		// Only auto-upgrade standard web ports, otherwise preserve the upstream scheme.
		if (!in_array($port, [80, 443], true)) {
			return $url;
		}

		$httpsUrl = preg_replace('/^http:/i', 'https:', $url, 1);

		return $httpsUrl ?? $url;
	}

	private function streamUpstreamResponse($response, string $url)
	{
		$contentType = $response->header('Content-Type');
		if (str_contains($url, '.ts')) {
			$contentType = 'video/mp2t';
		} elseif (str_contains($url, '.m4s') || str_contains($url, '.mp4')) {
			$contentType = 'video/mp4';
		}

		return response()->stream(function () use ($response) {
			$body = $response->toPsrResponse()->getBody();
			while (!$body->eof()) {
				echo $body->read(8192);
				flush();
			}
		}, $response->status(), array_filter([
			'Content-Type' => $contentType ?: 'application/octet-stream',
			'Content-Length' => $response->header('Content-Length'),
			'Content-Range' => $response->header('Content-Range'),
			'Accept-Ranges' => 'bytes',
			'Cache-Control' => $this->segmentCacheControlHeader($url),
			'Connection' => 'keep-alive',
			'ETag' => $response->header('ETag'),
			'Last-Modified' => $response->header('Last-Modified'),
		]));
	}

	private function cachedStream(string $streamId): Content
	{
		return Cache::remember(
			"stream_proxy_meta_{$streamId}",
			now()->addMinutes(10),
			fn () => Content::findOrFail($streamId)
		);
	}

	private function resolveProxySourceUrl(Content $content): string
	{
		$url = (string) ($content->stream_video_link ?: $content->stream_url);

		if ($url === '') {
			abort(404, 'Stream source not found');
		}

		return $this->normalizeUpstreamUrl($url);
	}

	private function playlistCacheKey(string $streamId, string $url): string
	{
		return 'stream_playlist:' . $streamId . ':' . sha1($url);
	}

	private function getCachedPlaylistResponse(string $cacheKey)
	{
		$cached = Cache::get($cacheKey);

		if (!is_array($cached) || !isset($cached['body'], $cached['content_type'])) {
			return null;
		}

		return response($cached['body'], 200)->withHeaders([
			'Content-Type' => $cached['content_type'],
			'Cache-Control' => $this->playlistCacheControlHeader(),
			'X-Nowstream-Cache' => 'HIT',
		]);
	}

	private function cachePlaylistResponse(string $cacheKey, string $body, string $contentType, bool $storeInCache)
	{
		if ($storeInCache) {
			Cache::put($cacheKey, [
				'body' => $body,
				'content_type' => $contentType,
			], now()->addSeconds($this->playlistCacheTtlSeconds()));
		}

		return response($body, 200)->withHeaders([
			'Content-Type' => $contentType,
			'Cache-Control' => $this->playlistCacheControlHeader(),
			'X-Nowstream-Cache' => $storeInCache ? 'MISS' : 'BYPASS',
		]);
	}

	private function playlistCacheTtlSeconds(): int
	{
		$ttl = (int) config('custom.STREAM.PLAYLIST_CACHE_TTL_SECONDS', 3);

		return max(1, $ttl);
	}

	private function segmentCacheTtlSeconds(): int
	{
		$ttl = (int) config('custom.STREAM.SEGMENT_CACHE_TTL_SECONDS', 120);

		return max(10, $ttl);
	}

	private function playlistCacheControlHeader(): string
	{
		$ttl = $this->playlistCacheTtlSeconds();

		return "public, max-age={$ttl}, s-maxage={$ttl}, stale-while-revalidate=10";
	}

	private function segmentCacheControlHeader(string $url): string
	{
		if (str_contains($url, '.m3u8')) {
			return $this->playlistCacheControlHeader();
		}

		$ttl = $this->segmentCacheTtlSeconds();

		return "public, max-age={$ttl}, s-maxage={$ttl}, stale-while-revalidate=30";
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
			$email = $username . '@streamer.co.ke';

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
				'Welcome to streamer.co.ke . Kindly update your profile to enjoy a better experience.'
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
            return redirect()->route('stream.show', ['uuid' => $stream->uuid, 'slug' => $stream->slug]);
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
					return redirect()->route('stream.show', ['uuid' => $stream->uuid, 'slug' => $stream->slug]);
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
        // Fetch UUID
        $uuid = Content::where('content_group', 'livestream')
                       ->where('slug', $slug)
                       ->value('uuid');

        if (!$uuid) abort(404, 'Content not found');

        // Cache the main stream (static info only)
        $stream = Cache::remember("stream_{$uuid}", now()->addDay(), function () use ($uuid) {
            return Content::where('uuid', $uuid)->firstOrFail();
        });

        $user = Auth::user();

        // Viewer tracking (dynamic)
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
            $stream->increment('views');
        }

        // Related streams (cache static list only)
        $streams = Cache::remember("related_streams_{$uuid}", now()->addDay(), function () use ($uuid) {
            return Content::where('status', 1)
                ->where('uuid', '<>', $uuid)
                ->where('content_group', 'livestream')
                ->take(4)
                ->get();
        });

        // Channels & videos (cache)
        $channels = Cache::remember('channels_top_8', now()->addDay(), fn() =>
            Channel::where('status', 1)->take(8)->get()
        );

        $videos = Cache::remember('videos_top_12', now()->addDay(), fn() =>
            Content::where('type', 'video')->take(12)->get()
        );

        // Comments are dynamic per stream — do NOT cache
        $comments = $stream->comments()->with('user')->latest()->get();
        $streamProxyUrl = URL::temporarySignedRoute('stream.view', now()->addMinutes($this->streamProxyTtlMinutes()), [
            'streamId' => $stream->uuid,
        ]);

        return view('Frontend.modules.channels.streams.stream', compact(
            'stream', 'streams', 'channels', 'videos', 'comments', 'streamProxyUrl'
        ));

    } catch (\Exception $e) {
        Log::error('Content not found: ' . $e->getMessage());
        abort(404, 'Content not found');
    }
}
public function show($uuid, $slug = "")
{
    try {
        // Cache the main stream (static info only)
        $stream = Cache::remember("stream_{$uuid}", now()->addDay(), function () use ($uuid) {
            return Content::where('uuid', $uuid)->firstOrFail();
        });

        $user = Auth::user();

        // Event subscription check for logged-in users
        if ($user && $stream->event_id) {
            $order = Order::query()
                ->forPaidEventProductType($user->id, $stream->event_id, 'content')
                ->first();
            $subscription = null;
            if (Schema::hasTable('subscriptions')) {
                $subscription = Subscription::where('user_id', $user->id)
                    ->where('event_id', $stream->event_id)
                    ->where('status', 1)
                    ->where('type', 'stream')
                    ->first();
            }

            if (!$order && !$subscription) {
                $event = $stream->event;

                if ($event) {
                    return redirect()->route('event.show', ['slug' => $event->slug]);
                }

                return redirect()->route('events');
            }
        }

        // Viewer tracking (dynamic)
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

        // Related streams (cached static)
        $streams = Cache::remember("related_streams_{$uuid}", now()->addDay(), function () use ($uuid) {
            return Content::where('status', 1)
                ->where('uuid', '<>', $uuid)
                ->where('content_group', 'livestream')
                ->take(4)
                ->get();
        });

        // Channels & videos (cached)
        $channels = Cache::remember('channels_top_8', now()->addDay(), fn() =>
            Channel::where('status', 1)->take(8)->get()
        );

        $videos = Cache::remember('videos_top_12', now()->addDay(), fn() =>
            Content::where('type', 'video')->take(12)->get()
        );

        // Comments are dynamic per stream — do NOT cache
        $comments = $stream->comments()->with('user')->latest()->get();
        $streamProxyUrl = URL::temporarySignedRoute('stream.view', now()->addMinutes($this->streamProxyTtlMinutes()), [
            'streamId' => $stream->uuid,
        ]);

        return view('Frontend.modules.channels.streams.stream', compact(
            'stream', 'streams', 'channels', 'videos', 'comments', 'streamProxyUrl'
        ));

    } catch (\Exception $e) {
        Log::error('Stream not found: ' . $e->getMessage());
        abort(404, 'Content not found');
    }
}

private function streamProxyTtlMinutes(): int
{
    $ttl = (int) config('custom.STREAM.PROXY_TTL_MINUTES', 30);
    return max(1, $ttl);
}

}
