<?php


use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAppKey;
use App\Http\Middleware\CheckEventPayment;
    use App\Http\Middleware\ChooseChannel;
    use App\Http\Middleware\Cors;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\GetRegion;
use App\Http\Middleware\PasswordExpired;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Http\Middleware\ValidatePostSize;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web     : __DIR__ . '/../routes/web.php',
                      commands: __DIR__ . '/../routes/console.php',
                      channels: __DIR__ . '/../routes/channels.php',
                      health  : '/up',
                      api     : __DIR__ . '/../routes/api.php',
                      then    : function ()
                          {

                              Route::prefix('malipo')
                                   ->middleware(['api'])
                                   ->group(base_path('routes/mpesa.php'));

                              Route::prefix('malipo')
                                   ->middleware(['api'])
                                   ->group(base_path('routes/dpo.php'));

                          },

                  )
                  ->withMiddleware(function (Middleware $middleware): void
                      {
                          $middleware->append(TrustHosts::class);
                          $middleware->append(TrustProxies::class);
                          $middleware->append(HandleCors::class);
                          $middleware->append(PreventRequestsDuringMaintenance::class);
                          $middleware->append(ValidatePostSize::class);
                          $middleware->append(TrimStrings::class);
                          $middleware->append(ConvertEmptyStringsToNull::class);
                          $middleware->append(Cors::class);


                          // Middleware groups
                          $middleware->appendToGroup('web', [
                              EncryptCookies::class,
                              AddQueuedCookiesToResponse::class,
                              StartSession::class,
                              ShareErrorsFromSession::class,
                              VerifyCsrfToken::class,
                              SubstituteBindings::class,
                              AuthenticateSession::class,

                          ]);

                          $middleware->appendToGroup('api', [
                              ForceJsonResponse::class,
                              EnsureFrontendRequestsAreStateful::class,
                              ThrottleRequests::class . ':api',
                              SubstituteBindings::class,

                          ]);

                          // Aliases
                          $middleware->alias([
                              'detectCountry'       => GetRegion::class,
                              'passkey'             => CheckAppKey::class,
                              'cors'                => Cors::class,
                              'force_json'          => ForceJsonResponse::class,
                              'cache.headers'       => SetCacheHeaders::class,
                              'can'                 => Authorize::class,
                              'guest'               => RedirectIfAuthenticated::class,
                              'password.confirm'    => RequirePassword::class,
                              'precognitive'        => HandlePrecognitiveRequests::class,
                              'signed'              => ValidateSignature::class,
                              'throttle'            => ThrottleRequests::class,
                              'verified'            => EnsureEmailIsVerified::class,
                              'password.expired'    => PasswordExpired::class,
                              'check.event.payment' => CheckEventPayment::class,
                              'auth'                => Authenticate::class,
                              'choose.channel'      => ChooseChannel::class,


                          ]);

                      })
                  ->withExceptions(function (Exceptions $exceptions): void
                      {
                          //
                      })->create();
