<?php

	use App\Http\Controllers\API\APIController;
	use App\Http\Controllers\API\MobileAuthController;
	use App\Http\Controllers\API\TvAppContentApiController;
	use App\Http\Controllers\Api\CartApiController;
	use App\Http\Controllers\Api\MerchandiseApiController;
	use App\Http\Controllers\Api\OrderApiController;
	use App\Http\Controllers\Callbacks\DPOCallbackController;
	use Illuminate\Support\Facades\Route;
	use App\Http\Controllers\Api\PodcastApiController;

	Route::prefix('podcasts')->group(function () {

		// List podcasts
		Route::get('/', [PodcastApiController::class, 'index']);

		// Single podcast
		Route::get('/{slug}', [PodcastApiController::class, 'show']);

		// Podcast episodes
		Route::get('/{slug}/episodes', [PodcastApiController::class, 'episodes']);

		// Record watch history
		Route::post('/watch-history', [PodcastApiController::class, 'recordWatchHistory']);

	});

	Route::prefix('tv-app')->group(function () {
		Route::get('home', [TvAppContentApiController::class, 'home']);
		Route::get('featured', [TvAppContentApiController::class, 'featured']);
		Route::get('categories', [TvAppContentApiController::class, 'categories']);
		Route::get('categories/{slug}/contents', [TvAppContentApiController::class, 'categoryContents']);
		Route::get('events', [TvAppContentApiController::class, 'events']);
		Route::get('events/{slug}/contents', [TvAppContentApiController::class, 'eventContents']);
		Route::get('regions', [TvAppContentApiController::class, 'regions']);
		Route::get('regions/{regionId}/contents', [TvAppContentApiController::class, 'regionContents']);
	});
	/*
	|--------------------------------------------------------------------------
	| API Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register API routes for your application. These
	| routes are loaded by the RouteServiceProvider and all of them will
	| be assigned to the "api" middleware group. Make something great!
	|
	*/

	Route::post('auth',[APIController::class,'login']);
	Route::get('auth/social/{provider}/redirect', [MobileAuthController::class, 'socialRedirect'])
		->whereIn('provider', ['facebook', 'google', 'twitter', 'x'])
		->name('api.auth.social.redirect');
	Route::get('auth/social/{provider}/callback', [MobileAuthController::class, 'socialCallback'])
		->whereIn('provider', ['facebook', 'google', 'twitter', 'x'])
		->name('api.auth.social.callback');
	Route::post('auth/phone/request-otp', [MobileAuthController::class, 'requestPhoneOtp']);
	Route::post('auth/phone/verify-otp', [MobileAuthController::class, 'verifyPhoneOtp']);
	Route::get('products', [MerchandiseApiController::class, 'index']);
	Route::get('products/{product}', [MerchandiseApiController::class, 'show']);
	Route::middleware(['auth:sanctum','passkey', 'force_json', 'cors'])->group(function () {
		Route::post('msisdn_decrypt',[APIController::class,'decrypt_msisdn']);
		Route::post('user-subscriptions',[APIController::class,'check_user_subscriptions']);
		Route::get("check-subscription/{identifier}",[APIController::class,'check_specific_subscription']);
		Route::get("cancel-subscription/{identifier}",[APIController::class,'cancel_subscription']);
		Route::get('cart', [CartApiController::class, 'index']);
		Route::post('cart', [CartApiController::class, 'store']);
		Route::patch('cart/{cartItem}', [CartApiController::class, 'update']);
		Route::delete('cart/{cartItem}', [CartApiController::class, 'destroy']);
		Route::post('checkout/orders', [OrderApiController::class, 'store']);
		Route::get('checkout/orders/{order}', [OrderApiController::class, 'show']);
	});
    Route::post('content/{content}/failure', [APIController::class, 'disableContent'])->name('api.disable-content');
