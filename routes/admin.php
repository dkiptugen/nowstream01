<?php

use App\Http\Controllers\Auth\Admin\AuthController;
use App\Http\Controllers\Auth\Admin\ConfirmPasswordController;
use App\Http\Controllers\Auth\Admin\ExpiredPasswordController;
use App\Http\Controllers\Auth\Admin\ForgotPasswordController;
use App\Http\Controllers\Auth\Admin\LoginController;
use App\Http\Controllers\Auth\Admin\OutletController;
use App\Http\Controllers\Auth\Admin\RegisterController;
use App\Http\Controllers\Auth\Admin\ResetPasswordController;
use App\Http\Controllers\Auth\Admin\VerificationController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\EventStreamController;
use App\Http\Controllers\Backend\EventVideoController;
use App\Http\Controllers\Backend\MicrositeController;
use App\Http\Controllers\Backend\PodcastController;
use App\Http\Controllers\Backend\PodcastEpisodeController;
use App\Http\Controllers\Backend\RadioController;
use App\Http\Controllers\Backend\RateController;
use App\Http\Controllers\Backend\StreamController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\TvController;
use App\Http\Controllers\Backend\VideoController;
use App\Http\Controllers\Backend\LogsController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\PaymentMethodController;
use Illuminate\Support\Facades\Route;


Route::name('admin.')->prefix('admin')->middleware(['web'])->group(function ()
    {
        Route::controller(LoginController::class)->group(function ()
            {
                Route::get('/login', 'showLoginForm')->middleware('guest:admin')->name('login.form');
                Route::post('/login', 'login')->middleware('guest:admin')->name('login')->secure();
                Route::post('/logout', 'logout')->name('logout')->middleware('auth:admin');
            });
        Route::controller(AuthController::class)->group(function ()
            {
                Route::get('social/{social}', 'redirectToProvider')->name('social');
                Route::get('social/{social}/callback', 'handleProviderCallback')->name('social_callback');
                Route::any('social/{social}/delete/{id}', 'deleteProviderCallback')->name('social_delete');
            });
        Route::controller(RegisterController::class)->group(callback: function ()
            {
                Route::get('register', 'showRegistrationForm')->name('register');
                Route::post('register', 'register');
            });

        Route::controller(ResetPasswordController::class)->group(callback: function ()
            {
                Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
                Route::post('password/reset', 'reset')->name('password.update');
            });
        Route::controller(ForgotPasswordController::class)->group(callback: function ()
            {
                Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
                Route::post('password/email', 'sendResetLinkEmail')->name('password.email');
            });

        Route::controller(VerificationController::class)->group(callback: function ()
            {
                Route::get('email/verify', 'show')->name('verification.notice');
                Route::get('email/verify/{id}/{hash}', 'verify')->name('verification.verify');
                Route::post('email/resend', 'resend')->name('verification.resend');

            });

        Route::controller(ConfirmPasswordController::class)->group(callback: function ()
            {
                Route::get('password/confirm', 'showConfirmForm')->name('password.confirm');
                Route::post('password/confirm', 'confirm');
            });


    });
Route::middleware(['auth:admin'])->name('backend.')->prefix('backend')->controller(OutletController::class)->group(function ()
    {
        Route::get('/choose-brand', 'choose_brand')->name('choose_brand');
        Route::post('/select-brand', 'select_brand')->name('select_brand');
        Route::get('/create-brand', 'create_brand_view')->name('create_brand');
        Route::post('/create-brand', 'store_brand')->name('store_brand');
    });
Route::middleware(['auth:admin', 'choose.channel'])->prefix('backend')->name('backend.')->group(function ()
    {

        Route::get('/', [DashboardController::class, 'index'])->name('admin_dashboard');

        Route::get('/change_channel/{channel}', [OutletController::class, 'brand_change'])->name('change_brand');

        Route::controller(CategoryController::class)->group(function ()
            {
                Route::resource('category', CategoryController::class)->except(['show']);
                Route::post('category/datatable', 'datatable')->name('category.datatable');
            });

        Route::controller(TvController::class)->group(function ()
            {
                Route::resource('tv', TvController::class);
                Route::post('tv/datatable', 'datatable')->name('tv.datatable');
            });

        Route::controller(RadioController::class)->group(function ()
            {
                Route::resource('radio', RadioController::class);
                Route::post('radio/datatable', 'datatable')->name('radio.datatable');
            });

        Route::controller(PodcastController::class)->group(function ()
            {
                Route::resource('podcast', PodcastController::class)->except(['show']);
                Route::post('podcast/datatable', 'datatable')->name('podcast.datatable');
            });
        Route::controller(PodcastEpisodeController::class)->group(function ()
            {
                Route::resource('podcast.episode', PodcastEpisodeController::class);
                Route::post('podcast/{podcast}/datatable', 'datatable')->name('podcast.episode.datatable');
            });

        Route::controller(EventController::class)->group(function ()
            {
                Route::resource('event', EventController::class);
                Route::post('event/datatable', 'datatable')->name('event.datatable');
            });

        Route::controller(StreamController::class)->group(function ()
            {
                Route::resource('stream', StreamController::class);
                Route::post('stream/datatable', 'datatable')->name('stream.datatable');
            });

        Route::controller(VideoController::class)->group(function ()
            {
                Route::resource('video', VideoController::class);
                Route::post('video/datatable', 'datatable')->name('video.datatable');
            });
        Route::controller(MicrositeController::class)->group(function ()
            {
                Route::resource('microsite', MicrositeController::class);
                Route::post('microsite/datatable', 'datatable')->name('microsite.datatable');
            });

        Route::name('event.')->prefix('event')->group(function ()
            {
                Route::controller(EventStreamController::class)->group(function ()
                    {
                        Route::resource('{event}/stream', EventStreamController::class);
                        Route::post('{event}/stream/datatable', 'datatable')->name('stream.datatable');
                    });

                Route::controller(EventVideoController::class)->group(function ()
                    {
                        Route::resource('{event}/video', EventVideoController::class);
                        Route::post('{event}/video/datatable', 'datatable')->name('video.datatable');
                    });
                Route::controller(RateController::class)->group(function (){
                    Route::resource('{event}/rate', RateController::class);
                    Route::post('{event}/rate/datatable', 'datatable')->name('rate.datatable');
                });
            });


        Route::controller(ConfigurationController::class)->group(function ()
            {
                Route::get('/configuration', 'index')->name('configuration.index');
                Route::post('/configuration/edit', 'edit')->name('configuration.edit');
            });

        Route::middleware(['auth', 'is_owner'])->group(function ()
            {
                Route::get('password/expired', [ExpiredPasswordController::class, 'expired'])->name('password.expired');
                Route::post('password/post_expired', [ExpiredPasswordController::class, 'postExpired'])->name('password.post_expired');
            });

        Route::controller(PaymentMethodController::class)->group(function ()
            {
                Route::resource('payment_method', PaymentMethodController::class, ['except' => ['show']]);
                Route::post('/payment_methods/datatable', 'datatable')->name('payment_method.datatable');
            });

        Route::controller(RolesController::class)->group(function ()
            {
                Route::resource('role', RolesController::class);
                Route::post('roles/datatable', 'datatable')->name('role.datatable');
                Route::get('roles/{id}/assign', 'assign_view')->name('role.assign_view');
                Route::post('roles/{id}/assign', 'assign')->name('role.assign');
            });


        Route::controller(LogsController::class)->group(function ()
            {
                Route::resource('logs', LogsController::class);
                Route::post('/logs/datatable', 'datatable')->name('logs.datatable');
            });


        Route::controller(UserController::class)->group(function ()
            {
                Route::resource('user', UserController::class);
                Route::post('/user/datatable', 'datatable')->name('user.datatable');
            });


        Route::controller(TransactionController::class)->group(function ()
            {
                Route::resource('transaction', TransactionController::class);
                Route::post('transaction/datatable', 'datatable')->name('transaction.datatable');

            });
        Route::controller(ProfileController::class)->group(function ()
            {
                Route::get('profile', 'index')->name('profile.index');
                Route::put('profile-update', 'update')->name('profile.update');
            });


    });
