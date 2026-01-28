<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
    {
        public function handle($request, Closure $next, ...$guards)
            {

                foreach ($guards as $guard)
                    {
                        if (Auth::guard($guard)->check())
                            {
                                Auth::shouldUse($guard);
                                return $next($request);
                            }
                        else
                            {
                                if (Auth::guard('admin')->guest())
                                    {
                                        return route('admin.login');
                                    }
                                if (Auth::guard('user')->guest() || Auth::guard('web')->guest())
                                    {
                                        return route('user.login');
                                    }
                            }
                    }

                return $this->unauthenticated($request, $guards);

            }

    /**
     * Determine the path to redirect unauthenticated users.
     */
        protected function redirectTo(Request $request): ?string
            {
                // If this is an API request, do not redirect
                if ($request->expectsJson())
                    {
                        return null;
                    }
                if (Auth::guard('admin')->guest())
                    {
                        return route('admin.login');
                    }
                if (Auth::guard('user')->guest() || Auth::guard('web')->guest())
                    {
                        return route('user.login');
                    }
            }

    /**
     * Retrieve the first guard from the middleware call or default to 'web'.
     */
        protected function getGuard(Request $request, ...$guards)
            {
                // The $guards array comes from the middleware call, e.g., 'auth:admin,user'


                // Return the first guard in the list
                return $guards;
            }
    }
