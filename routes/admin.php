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
use App\Http\Controllers\Backend\ChannelStreamController;
use App\Http\Controllers\Backend\ChannelSubscriptionController;
use App\Http\Controllers\Backend\ChannelTransactionController;
use App\Http\Controllers\Backend\ChannelVideoController;
use App\Http\Controllers\Backend\EventRateController;
use App\Http\Controllers\Backend\LogsController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\StreamPartnerController;
use App\Http\Controllers\Backend\StreamPartnerRateController;
use App\Http\Controllers\Backend\SubscriptionController;
use App\Http\Controllers\Backend\TransactionController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\ChannelController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EventController;
use App\Http\Controllers\Backend\ConfigurationController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\VideoController;
use App\Http\Controllers\Backend\StreamController;
use Illuminate\Support\Facades\Route;


Route::name('admin.')->middleware(['web'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login',  'showLoginForm')->middleware('guest:admin')->name('login');
        Route::post('/login',  'login')->middleware('guest:admin')->name('login')->secure();
        Route::post('/logout', 'logout')->name('logout');
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

Route::prefix('backend')->group(function () {
Route::controller(OutletController::class)->middleware(['auth:admin'])->group(function () {
    Route::get('/choose_channel',  'selectOutlet')->name('choose_outlet');
    Route::post('/select',  'saveOutlet')->name('save_outlet');
});

Route::prefix('backend')->middleware(['auth:admin', 'choose.channel','check.channels'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin_dashboard');

    Route::get('/change_channel/{channel}', [OutletController::class, 'outlet_change'])->name('change_channel');


    Route::resource('/channel', ChannelController::class)->except(['show']);
    Route::post('/channel/datatable', [ChannelController::class, 'datatable'])->name('channel.datatable')->secure();


    Route::middleware('belongs.to')->group(function () {
        Route::resource('channel.event', EventController::class);
        Route::post('channel/{channel}/event/datatable', [EventController::class, 'datatable'])->name('channel.event.datatable');

        Route::resource('channel.stream', ChannelStreamController::class);
        Route::post('channel/{channel}/stream/datatable', [ChannelStreamController::class, 'datatable'])->name('channel.stream.datatable');

        // Route::get('channel/{channelId}/video/create', [ChannelVideoController::class, 'create'])->name('channel.videos.create');
        // Route::post('channel/{channelId}/video/store', [ChannelVideoController::class, 'store'])->name('channel.videos.store');
        // Route::get('channel/video/{id}/edit', [ChannelVideoController::class, 'edit'])->name('channel.videos.edit');
        // Route::put('channel/video/{id}', [ChannelVideoController::class, 'update'])->name('channel.videos.update');
        // Route::delete('channel/video/{id}', [ChannelVideoController::class, 'destroy'])->name('channel.videos.destroy');

        Route::resource('channel.video', ChannelVideoController::class);
        Route::post('channel/{channel}/video/datatable', [ChannelVideoController::class, 'datatable'])->name('channel.video.datatable');


    });


    Route::resource('event.stream', StreamController::class);
    Route::post('event/{evenId}/stream/datatable', [StreamController::class, 'datatable'])->name('event.stream.datatable');

    Route::resource('event.rates', EventRateController::class);
    Route::post('event/{evenId}/rate/datatable', [EventRateController::class, 'datatable'])->name('event.rate.datatable');

    Route::get('event/{evenId}/rate/edit', [EventRateController::class, 'edit'])->name('event.rate.edit');

    Route::resource('event.video', VideoController::class);
    Route::post('event/{evenId}/video/datatable', [VideoController::class, 'datatable'])->name('event.video.datatable');

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

    Route::resource('stream_partner', StreamPartnerController::class);
    Route::post('/stream_partner/datatable', [StreamPartnerController::class, 'datatable'])->name('stream_partner.datatable');

    Route::resource('stream_partner_rate', StreamPartnerRateController::class);
    Route::post('/stream_partner_rate/datatable', [StreamPartnerRateController::class, 'datatable'])->name('stream_partner_rate.datatable');

    Route::resource('user', UserController::class);
    Route::post('/user/datatable', [UserController::class, 'datatable'])->name('user.datatable');

    Route::resource('subscription', SubscriptionController::class);
    Route::post('/subscription/datatable', [SubscriptionController::class, 'datatable'])->name('subscription.datatable');

    Route::resource('transaction', TransactionController::class);
    Route::post('/transaction/datatable', [TransactionController::class, 'datatable'])->name('transaction.datatable');

    Route::resource('channel.subscription', ChannelSubscriptionController::class);
    Route::post('channel/{channel}/subscription/datatable', [ChannelSubscriptionController::class, 'datatable'])->name('channel.subscription.datatable');

    Route::resource('channel.transaction', ChannelTransactionController::class);
    Route::post('channel/{channel}/transaction/datatable', [ChannelTransactionController::class, 'datatable'])->name('channel.transaction.datatable');

    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('profile-update', [ProfileController::class, 'update'])->name('admin.profile.update');
});
});
