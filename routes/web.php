<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Auth\User\AuthsController;
use App\Http\Controllers\Frontend\PodcastController as FrontendPodcastController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\TVController;
use App\Http\Controllers\Frontend\RadioController;
use App\Http\Controllers\Frontend\TicketController;
use App\Http\Controllers\Frontend\EventOrderController;
use App\Http\Controllers\Frontend\WatchHistoryController;
use App\Http\Controllers\Frontend\TenantController;
use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\StreamController;
use App\Http\Controllers\Frontend\CommentController;
use App\Http\Controllers\Frontend\StreamVideoController;
use App\Http\Controllers\Frontend\SubscriptionController;
use App\Http\Controllers\Frontend\VideoFavoriteController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\MerchandiseController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\MerchCheckoutController;
use Illuminate\Support\Str;

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


Route::middleware(['detectCountry'])->group(function ()
    {
        // Frontend Routes





        if (app()->environment('local'))
            {
                Route::prefix('/tenant')
                     ->name('tenant.')
                     ->controller(TenantController::class)
                     ->group(function ()
                         {
                             Route::get('/', 'index')->name('home');
                             Route::get('/events', 'events')->name('events');
                             Route::get('/tenant/event/{slug}', 'single_event')->name('single_event');
                             Route::get('/streams', 'streams')->name('streams');
                             Route::get('/stream/{stream}', 'single_stream')->name('single_stream');
                             Route::get('/mercherndise', 'merchendise')->name('merchendise');
                             Route::get('/merchendise/{merchendise}', 'single_merchendise')->name('single_merchendise');
                         });
                //dd('test');
                Route::middleware("web")
                     ->group(function ()
                         {
                             Route::get('/', [HomeController::class, 'index'])->name('home');
                             Route::get('/events', [EventController::class, 'index'])->name('events');
                             Route::get('/event', [EventController::class, 'show']);
                             Route::get('/all-videos', [StreamVideoController::class, 'index'])->name('videos');
                             Route::get('/streams', [StreamController::class, 'index']);
                             Route::get('/newvideo', [StreamVideoController::class, 'newvideo']);
                         });
            }
        else
            {
                Route::domain('{subdomain}.' . config('app.base_domain'))
                     ->middleware(['tenant'])
                     ->name('tenant.')
                     ->controller(TenantController::class)
                     ->group(function ()
                         {
                             Route::get('/', 'index')->name('home');
                             Route::get('/events', 'events')->name('events');
                             Route::get('/tenant/event/{slug}', 'single_event')->name('single_event');
                             Route::get('/streams', 'streams')->name('streams');
                             Route::get('/stream/{stream}', 'single_stream')->name('single_stream');
                             Route::get('/mercherndise', 'merchendise')->name('merchendise');
                             Route::get('/merchendise/{merchendise}', 'single_merchendise')->name('single_merchendise');
                         });

                Route::domain(config('app.base_domain'))
                     ->group(function ()
                         {
                             Route::get('/', [HomeController::class, 'index'])->name('home');
                             Route::get('/events', [EventController::class, 'index'])->name('events');
                             Route::get('/event', [EventController::class, 'show']);
                             Route::get('/all-videos', [StreamVideoController::class, 'index'])->name('videos');
                             Route::get('/streams', [StreamController::class, 'index']);
                             Route::get('/newvideo', [StreamVideoController::class, 'newvideo']);
                         });
            }

        Route::get('/somalinite', [HomeController::class, 'landing']);


        Route::get('/search', [SearchController::class, 'search'])->name('search');
        Route::get('/shop', [MerchandiseController::class, 'index'])->name('shop.index');
        Route::get('/shop/{product}', [MerchandiseController::class, 'show'])->name('shop.show');
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
        Route::post('/watch-history/{uuid}', [WatchHistoryController::class, 'store'])
             ->middleware('auth'); // Only logged-in users
        Route::name('user.')->prefix('user')->controller(AuthsController::class)->group(function ()
            {
                Route::get('/partner/register', 'partner');
                Route::get('social/{social}', 'redirectToProvider')->name('auth.social');
                Route::get('social/{social}/callback', 'handleProviderCallback')->name('auth.social_callback');
                Route::any('social/{social}/delete', 'deleteProviderCallback')->name('auth.social_delete');

                // Authentication routes

                Route::get('register', 'showRegisterForm')->name('register.form');
                Route::post('register', 'register')->name('register');
                Route::get('phone-login', 'showPhoneLoginForm')->name('phoneform');
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
                Route::get('/email/verify', function ()
                    {
                        return view('Frontend.auth.verify-email');
                    })->middleware(['auth'])->name('verification.notice')->name('verification.notice');
                Route::get('/email/verify/{id}/{hash}', 'verifyEmail')
                     ->middleware(['auth', 'signed'])
                     ->name('verification.verify');
                Route::post('/email/verification-notification', function ()
                    {
                        request()->user()->sendEmailVerificationNotification();
                        return back()->with('message', 'Verification link sent!');
                    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
            });
        Route::post('/stream/find', [StreamController::class, 'findStream'])->name('stream.find');
        Route::get('stream/{streamId}/view', [StreamController::class, 'proxy_stream'])->name('stream.view');
        Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::middleware(['auth:web'])->group(function ()
            {

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
                Route::get('/video/{uuid}/{slug?}', [StreamVideoController::class, 'show'])
                     ->name('video.show');

                Route::get('/video/file/{filename}', [StreamVideoController::class, 'get_video'])->name('video.file');
                Route::get('/event/pay/{eventId}/{rate_id}', [EventController::class, 'pay'])->name('event.pay');
                Route::post('/event/checkout', [EventOrderController::class, 'checkout'])->name('event.checkout');
                Route::get('/event/payment/mpesa/{order}', [EventOrderController::class, 'mpesa'])->name('event.payment.mpesa');
                Route::post('/event/payment/mpesa', [EventOrderController::class, 'mpesaStk'])->name('event.payment.mpesa.stk');
                Route::get('/event/success/{eventId}', [EventOrderController::class, 'success'])->name('event.success');
                Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
                Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
                Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
                Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
                Route::get('/shop/checkout', [MerchCheckoutController::class, 'create'])->name('shop.checkout');
                Route::post('/shop/checkout', [MerchCheckoutController::class, 'store'])->name('shop.checkout.store');
                Route::get('/shop/payment/mpesa/{order}', [MerchCheckoutController::class, 'mpesa'])->name('shop.payment.mpesa');
                Route::post('/shop/payment/mpesa', [MerchCheckoutController::class, 'mpesaStk'])->name('shop.payment.mpesa.stk');
                Route::get('/shop/success/{order}', [MerchCheckoutController::class, 'success'])->name('shop.success');
                Route::post('subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
                Route::get('mpesa/{id}', [SubscriptionController::class, 'mpesa'])->name('mpesa');
                Route::post('mpesa/pay', [SubscriptionController::class, 'mpesaStk'])->name('mpesa_stk_pay');
                Route::get('dpo/{id}', [SubscriptionController::class, 'dpo'])->name('dpo');
                Route::get('/continue', [WatchHistoryController::class, 'watchedContent'])->name('watch.content');
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
        Route::get('/genre/tvs/{genre}', [CategoryController::class, 'genreTvs'])->name('genre.tvs');
        Route::get('/genre/radios/{genre}', [CategoryController::class, 'genreRadios'])->name('genre.radios');
        Route::get('/ticket/{uuid}', [TicketController::class, 'download'])
             ->name('ticket.download')
             ->middleware('auth');
        Route::get('/ticket/verify/{uuid}', [TicketController::class, 'verify'])
             ->name('ticket.verify');

        // show tv
        Route::get('/tv/{slug}', [TVController::class, 'show'])->name('tv.show');
        Route::get('/tvs', [TVController::class, 'index'])->name('tvs');
        // show radio
        Route::get('/radio/{slug}', [RadioController::class, 'show'])->name('radio.show');
        Route::get('/radios', [RadioController::class, 'index'])->name('radios');
        Route::post('/content/{uuid}/increment-views', [RadioController::class, 'incrementViews'])
             ->name('content.incrementViews');

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
    });
// Informational pages
Route::view('/faq', 'Frontend.pages.faq')->name('faq');
Route::view('/help-center', 'Frontend.pages.help-center')->name('help.center');
Route::view('/terms-of-use', 'Frontend.pages.terms')->name('terms');
Route::view('/privacy', 'Frontend.pages.privacy')->name('privacy');
Route::view('/user-data-deletion', 'Frontend.pages.user-data-deletion')->name('user.data.deletion');

Route::middleware('auth')->group(function ()
    {
        Route::post('comment/{comment}/like', [CommentController::class, 'like'])->name('comment.like');
        Route::post('comment/{comment}/dislike', [CommentController::class, 'dislike'])->name('comment.dislike');
    });

Route::get('/podcasts/load-more', [FrontendPodcastController::class, 'loadmore'])->name('podcasts.loadMore');



require __DIR__ . '/admin.php';
