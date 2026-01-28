<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
    {
        public function handle(Request $request, Closure $next, ...$guards)
            {
                $guards = empty($guards) ? ['web'] : $guards;

                foreach ($guards as $guard) {
                    if (Auth::guard($guard)->check()) {
                        Auth::shouldUse($guard);
                        return $next($request);
                    }
                }

                // none matched → unauthenticated
                return $this->unauthenticated($request, $guards);
            }

        protected function redirectTo(Request $request): ?string
            {
                if ($request->expectsJson()) {
                    return null;
                }

                // Detect which guard was intended
                if (request()->route()?->middleware()) {
                    foreach (request()->route()->middleware() as $middleware) {
                        if (str_contains($middleware, 'auth:admin')) {
                            return route('admin.login');
                        }
                    }
                }

                return route('user.login');
            }
    }
