<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        api: __DIR__.'/../routes/api.php',
        then: function () {

            Route::group(['prefix'=>'malipo','middleware'=>['api']],function (){
                     base_path('routes/mpesa.php');
                    base_path('routes/dpo.php');
                 });

        },

    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\TrustHosts::class);
        $middleware->append(\App\Http\Middleware\TrustProxies::class);
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
        $middleware->append(\App\Http\Middleware\PreventRequestsDuringMaintenance::class);
        $middleware->append(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
        $middleware->append(\App\Http\Middleware\TrimStrings::class);
        $middleware->append(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);
        $middleware->append(\App\Http\Middleware\Cors::class);
        $middleware->append(\App\Http\Middleware\GetRegion::class);

        // Middleware groups
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);

        $middleware->appendToGroup('api', [
            \App\Http\Middleware\ForceJsonResponse::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\GetRegion::class,
        ]);

        // Aliases
        $middleware->alias([
            'detectCountry' => \App\Http\Middleware\GetRegion::class,
            'passkey' => \App\Http\Middleware\CheckAppKey::class,
            'cors' => \App\Http\Middleware\Cors::class,
            'force_json' => \App\Http\Middleware\ForceJsonResponse::class,
            'auth.user' => \App\Http\Middleware\RedirectIfNotUser::class,
            // add the rest of your aliases here...
            'admin.guard' => \App\Http\Middleware\AdminGuardMiddleware::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            'signed' => \App\Http\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'check.channels' => \App\Http\Middleware\CheckChannels::class,
            'password.expired' => \App\Http\Middleware\PasswordExpired::class,
            'belongs.to' => \App\Http\Middleware\BelongsToChannel::class,
            'choose.channel' => \App\Http\Middleware\ChooseChannelMiddleware::class,
            'auth.admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,

            'check.event.payment' => \App\Http\Middleware\CheckEventPayment::class,


        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
