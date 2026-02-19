<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Auth\User\AuthsController;
use App\Http\Controllers\Backend\PodcastController;
use App\Http\Controllers\Frontend\PodcastController as FrontendPodcastController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\TVController;
use App\Http\Controllers\Frontend\RadioController;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\StreamController;
use App\Http\Controllers\Frontend\ChannelController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\StreamVideoController;
use App\Http\Controllers\Frontend\SubscriptionController;
use App\Http\Controllers\Frontend\VideoFavoriteController;
use App\Http\Controllers\Frontend\CategoryController;
use Illuminate\Support\Str;
use Pusher\Pusher;

/*
       |--------------------------------------------------------------------------
       | Web Routes
       |--------------------------------------------------------------------------
       |
       | Here is where you can register web routes for your application. These
       | routes are loaded by the RouteServiceProvider and all of them will
       | be assigned to the "web" middleware group. Make something great!
       |
       */

Route::post('/pusher/auth', function (Request $request) {
    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    // Perform your user authentication logic here
    // Check if the user is authorized to subscribe to the private channel

    // Assuming the channelName follows the format "private-CHANNEL_NAME"
    $channelPrefix = 'private-';
    if (Str::startsWith($channelName, $channelPrefix)) {
        // Extract the actual channel name
        $actualChannel = substr($channelName, strlen($channelPrefix));

        // Create a new Pusher instance
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]
        );

        // Validate the user and generate the authentication token
        $auth = $pusher->socket_auth($channelName, $socketId);

        return response($auth);
    } else {
        // Unauthorized access to non-private channels
        return response('Forbidden', 403);
    }
});

Route::middleware(['detectCountry'])->group(function () {
    // Frontend Routes
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/somalinite', [HomeController::class, 'landing']);
    Route::get('/newvideo', [StreamVideoController::class, 'newvideo']);
    Route::get('/all-videos', [StreamVideoController::class, 'index'])->name('videos');
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/streams', [StreamController::class, 'index']);
    Route::get('/channels', [ChannelController::class, 'index']);
    Route::get('/channel', [ChannelController::class, 'show']);
    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/event', [EventController::class, 'show']);
    Route::get('/channel/{id}/{name}', [ChannelController::class, 'show'])->name('channel.show');
    // Route::post('/{commentableType}/{commentableId}/comment', [StreamVideoController::class, 'postComment'])->name('comment.post');
    Route::post(
        '/comment/post/{commentableType}/{commentableId}',
        [CommentController::class, 'postComment']
    )->name('comment.post');

    // Fetch comments for a content item (UUID supported)
    Route::get(
        '/comment/fetch/{commentableType}/{commentableId}',
        [CommentController::class, 'fetchComments']
    )->name('comment.fetch');
    Route::post('/record-watch-history/{video}', [StreamVideoController::class, 'recordWatchHistory']);

    Route::name('user.')->prefix('user')->controller(AuthsController::class)->group(function () {
        Route::get('/partner/register', 'partner');
        Route::get('social/{social}', 'redirectToProvider')->name('auth.social');
        Route::get('social/{social}/callback', 'handleProviderCallback')->name('auth.social_callback');
        Route::any('social/{social}/delete', 'deleteProviderCallback')->name('auth.social_delete');

        // Authentication routes

        Route::get('register', 'showRegisterForm')->name('register.form');
        Route::post('register', 'register')->name('register');
        Route::get('phone-login', 'showPhoneLoginForm')->name('phonelogin.form');
        Route::post('phone-login', 'phoneLogin')->name('phonelogin');
        Route::get('phone-resend', 'phoneResend')->name('phoneresend');
        Route::post('otp_verification', 'otp_verify')->name('otp_verification');
        Route::get('login', 'showLoginForm')->name('login.form');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');

        // Password reset routes
        Route::post('/forgot-password', 'forgotPassword')->name('password.email');
        Route::post('/reset-password', 'resetPassword')->name('password.update');;
        // Email verification route
        Route::get('/email/verify', function () {
            return view('Frontend.auth.verify-email');
        })->middleware(['auth'])->name('verification.notice')->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
            ->middleware(['auth', 'signed'])
            ->name('verification.verify');
        Route::post('/email/verification-notification', function () {
            request()->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Verification link sent!');
        })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
    });
    Route::post('/stream/find', [StreamController::class, 'findStream'])->name('stream.find');
    Route::get('stream/{streamId}/view', [StreamController::class, 'proxy_stream'])->name('stream.view');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::middleware(['auth:web'])->group(function () {

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/password', [ProfileController::class, 'passwordEdit'])->name('profile.password.edit');
        Route::post('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('profile.password.update');
        Route::get('/stream/free/{slug?}', [StreamController::class, 'freeShow'])
            ->name('free.show');

        Route::get('/stream/{slug}', [StreamController::class, 'show'])
            ->name('stream.show');

        Route::post('/video/{video}/favorite', [VideoFavoriteController::class, 'favorite'])->name('video.favorite');
        Route::post('/video/{video}/unfavorite', [VideoFavoriteController::class, 'unfavorite'])->name('video.unfavorite');
        Route::get('/favorites', [VideoFavoriteController::class, 'myfavorite'])->name('video.myfavorite');
        Route::post('/channels/{channel}/subscribe', [ChannelController::class, 'subscribe'])->name('channels.subscribe');
        Route::post('/channels/{channel}/unsubscribe', [ChannelController::class, 'unsubscribe'])->name('channels.unsubscribe');
        Route::get('/video/{uuid}/{slug?}', [StreamVideoController::class, 'show'])
            ->name('video.show');


        Route::get('/video/file/{filename}', [StreamVideoController::class, 'get_video'])->name('video.file');
        Route::middleware(['check.event.payment'])->group(function () {
            Route::get('/event/pay/{eventId}/{rate_id}', [EventController::class, 'pay'])->name('event.pay');
        });
        Route::post('subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
        Route::get('mpesa/{id}', [SubscriptionController::class, 'mpesa'])->name('mpesa');
        Route::post('mpesa/pay', [SubscriptionController::class, 'mpesaStk'])->name('mpesa_stk_pay');
        Route::get('dpo/{id}', [SubscriptionController::class, 'dpo'])->name('dpo');
        Route::get('/continue', [StreamVideoController::class, 'watchedVideos']);
    });
    Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');

    // Content within a category (specific)
    Route::get('/category/{slug}/{contentGroup}', [CategoryController::class, 'contentCategory'])
        ->name('content.category');

    // Category main page
    Route::get('/category/{slug}', [CategoryController::class, 'show'])
        ->name('category.show');

    // Categories list
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    // show podcast
    Route::get('/podcast/{slug}', [FrontendPodcastController::class, 'show'])->name('podcast.show');
    Route::get('/podcasts', [FrontendPodcastController::class, 'index'])->name('podcasts');
    Route::get('/genre/{genre}', [CategoryController::class, 'genreContents'])->name('genre.show');

    // show tv
    Route::get('/tv/{slug}', [TVController::class, 'show'])->name('tv.show');
    Route::get('/tvs', [TVController::class, 'index'])->name('tvs');
    // show radio
    Route::get('/radio/{slug}', [RadioController::class, 'show'])->name('radio.show');
    Route::get('/radios', [RadioController::class, 'index'])->name('radios');

    // Social Auth Routes (Global)

    Route::get('auth/social/{provider}', [AuthsController::class, 'redirectToProvider'])
        ->whereIn('provider', ['facebook', 'twitter', 'google', 'linkedin'])
        ->name('auth.social');

    Route::get('auth/social/{provider}/callback', [AuthsController::class, 'handleProviderCallback'])
        ->whereIn('provider', ['facebook', 'twitter', 'google', 'linkedin'])
        ->name('auth.social_callback');

    Route::any('auth/social/{provider}/delete', [AuthsController::class, 'deleteProviderCallback'])
        ->whereIn('provider', ['facebook', 'twitter', 'google', 'linkedin'])
        ->name('auth.social_delete');

    Route::get('success/{eventId}', [SubscriptionController::class, 'succeed'])->name('success');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
});
// Informational pages
Route::view('/faq', 'Frontend.pages.faq')->name('faq');
Route::view('/help-center', 'Frontend.pages.help-center')->name('help.center');
Route::view('/terms-of-use', 'Frontend.pages.terms')->name('terms');
Route::view('/privacy', 'Frontend.pages.privacy')->name('privacy');
Route::view('/user-data-deletion', 'Frontend.pages.user-data-deletion')->name('user.data.deletion');

Route::middleware('auth')->group(function () {
    Route::post('comment/{comment}/like', [CommentController::class, 'like'])->name('comment.like');
    Route::post('comment/{comment}/dislike', [CommentController::class, 'dislike'])->name('comment.dislike');
});

Route::get('/podcasts/load-more', [PodcastController::class, 'loadMore'])->name('podcasts.loadMore');


require __DIR__ . '/admin.php';
