<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
    {
        public function handle($request, Closure $next, ...$guards)
            {
                $guards = empty($guards) ? ['web'] : $guards;

                foreach ($guards as $guard) {
                    if (Auth::guard($guard)->check()) {
                        Auth::shouldUse($guard);
                        return $next($request);
                    }
                }

                return $this->unauthenticated($request, $guards);
            }

        protected function redirectTo($request): ?string
            {
                if ($request->expectsJson()) {
                    return null;
                }

                // Detect admin guard from middleware
                foreach ($request->route()?->middleware() ?? [] as $middleware) {
                    if (str_contains($middleware, 'auth:admin')) {
                        return route('admin.login');
                    }
                }

                return route('user.login');
            }
    }
