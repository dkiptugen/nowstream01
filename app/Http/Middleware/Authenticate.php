<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
    {
        use AuthenticatesUsers;
    /**
     * Handle the redirection for unauthenticated users.
     */
        protected function redirectTo(Request $request): ?string
            {
                if ($request->expectsJson()) {
                    return null;
                }

                // Determine the guard
                $guard = $this->getGuard($request);

                return match($guard) {
                    'admin' => route('admin.login'),
                    default => route('user.login'),
                    };
            }

    /**
     * Get the guard from the route or default to 'web'.
     */
        protected function getGuard(Request $request): string
            {
                // Laravel passes guards array to authenticate method
                $guards = $this->guards ?: ['web'];
                return $guards[0]; // take the first guard
            }
    }
