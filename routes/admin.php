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
use App\Http\Controllers\Backend\ContentController;
use App\Http\Controllers\Backend\EventStreamController;
use App\Http\Controllers\Backend\EventVideoController;
use App\Http\Controllers\Backend\PodcastController;
use App\Http\Controllers\Backend\PodcastEpisodeController;
use App\Http\Controllers\Backend\RadioController;
use App\Http\Controllers\Backend\StreamController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\TvController;
use App\Http\Controllers\Backend\VideoController;
use App\Http\Controllers\Backend\EventRateController;
use App\Http\Controllers\Backend\LogsController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ChannelController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\PaymentMethodController;
use Illuminate\Support\Facades\Route;


Route::name('admin.')->prefix('admin')->middleware(['web'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login',  'showLoginForm')->middleware('guest:admin')->name('login.form');
        Route::post('/login',  'login')->middleware('guest:admin')->name('login')->secure();
        Route::post('/logout', 'logout')->name('logout')->middleware('auth:admin');
    });

    Route::get('social/{social}', [AuthController::class, 'redirectToProvider'])->name('social');
    Route::get('social/{social}/callback', [AuthController::class, 'handleProviderCallback'])->name('social_callback');
    Route::any('social/{social}/delete/{id}', [AuthController::class, 'deleteProviderCallback'])->name('social_delete');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);


});
Route::middleware(['auth:admin'])->name('backend.')->prefix('backend')->controller(OutletController::class)->group(function () {
    Route::get('/choose-channel',  'choose_channel')->name('choose_channel');
    Route::post('/select-channel',  'select_channel')->name('select_channel');
    Route::get('/create-channel',  'create_channel_view')->name('create_channel');
    Route::post('/create-channel',  'store_channel')->name('store_channel');
});
Route::middleware(['auth:admin','choose.channel'])->prefix('backend')->name('backend.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin_dashboard');

    Route::get('/change_channel/{channel}', [OutletController::class, 'channel_change'])->name('change_channel');

    Route::controller(CategoryController::class)->group( function () {
        Route::resource('category', CategoryController::class)->except(['show']);
        Route::post('category/datatable',  'datatable')->name('category.datatable');
    });

    Route::controller(TvController::class)->group( function () {
        Route::resource('tv', TvController::class);
        Route::post('tv/datatable',  'datatable')->name('tv.datatable');
    });

    Route::controller(RadioController::class)->group( function () {
        Route::resource('radio', RadioController::class);
        Route::post('radio/datatable', 'datatable')->name('radio.datatable');
    });

    Route::controller(PodcastController::class)->group( function () {
        Route::resource('podcast', PodcastController::class)->except(['show']);
        Route::post('podcast/datatable',  'datatable')->name('podcast.datatable');
    });
    Route::controller(PodcastEpisodeController::class)->group( function () {
        Route::resource('podcast.episode', PodcastEpisodeController::class);
        Route::post('podcast/{podcast}/datatable',  'datatable')->name('podcast.episode.datatable');
    });


    Route::controller(ChannelController::class)->group( function () {
        Route::resource('/channel', ChannelController::class)->except(['show']);
        Route::post('/channel/datatable',  'datatable')->name('channel.datatable');
    });

    Route::controller(EventController::class)->group( function (){
        Route::resource('event', EventController::class);
        Route::post('event/datatable',  'datatable')->name('event.datatable');
    });

    Route::controller(StreamController::class)->group( function (){
        Route::resource('stream', StreamController::class);
        Route::post('stream/datatable',  'datatable')->name('stream.datatable');
    });

    Route::controller(VideoController::class)->group( function (){
        Route::resource('video', VideoController::class);
        Route::post('video/datatable', 'datatable')->name('video.datatable');
    });
    Route::name('event.')->prefix('event')->group(function (){
        Route::controller(EventStreamController::class)->group( function (){
            Route::resource('stream', EventStreamController::class);
            Route::post('{event}/stream/datatable', 'datatable')->name('stream.datatable');
        });

        Route::controller(EventRateController::class)->group( function (){
            Route::resource('rate', EventRateController::class);
            Route::post('{event}/rate/datatable',  'datatable')->name('rate.datatable');
        });
        Route::controller(EventVideoController::class)->group( function (){
            Route::resource('video', EventVideoController::class);
            Route::post('{event}/video/datatable',  'datatable')->name('video.datatable');
        });
    });




















    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    Route::post('/configuration/edit', [ConfigurationController::class, 'edit'])->name('configuration.edit');

    Route::middleware(['auth', 'is_owner'])->group(function () {
        Route::get('password/expired', [ExpiredPasswordController::class, 'expired'])->name('password.expired');
        Route::post('password/post_expired', [ExpiredPasswordController::class, 'postExpired'])->name('password.post_expired');
    });

    Route::resource('payment_method', PaymentMethodController::class, ['except' => ['show']]);
    Route::post('/payment_methods/datatable', [PaymentMethodController::class, 'datatable'])->name('payment_method.datatable');

    Route::resource('role', RolesController::class);
    Route::post('roles/datatable', [RolesController::class, 'datatable'])->name('role.datatable');
    Route::get('roles/export', [RolesController::class, 'export_view'])->name('role.export_view');
    Route::get('roles/{id}/assign', [RolesController::class, 'assign_view'])->name('role.assign_view');
    Route::post('roles/{id}/assign', [RolesController::class, 'assign'])->name('role.assign');
    Route::post('roles/export', [RolesController::class, 'export'])->name('role.export');


    Route::resource('logs', LogsController::class);
    Route::post('/logs/datatable', [LogsController::class, 'datatable'])->name('logs.datatable');


    Route::resource('user', UserController::class);
    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');


    Route::resource('transaction', TransactionController::class);
    Route::post('transaction/datatable', [TransactionController::class, 'datatable'])->name('transaction.datatable');

    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile-update', [ProfileController::class, 'update'])->name('profile.update');

});
