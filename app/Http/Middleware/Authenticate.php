<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
    {
    /**
     * Determine the path to redirect unauthenticated users.
     */
        protected function redirectTo(Request $request): ?string
            {
                // If this is an API request, do not redirect
                if ($request->expectsJson()) {
                    return null;
                }

                // 🔑 detect guard
                if (in_array('admin', $this->getGuard())) {
                    return route('admin.login');
                }

                return route('user.login');
            }

    /**
     * Retrieve the first guard from the middleware call or default to 'web'.
     */
        protected function getGuard(Request $request): string
            {
                // The $guards array comes from the middleware call, e.g., 'auth:admin,user'
                $guards = $this->guards ?? ['web'];

                // Return the first guard in the list
                return $guards[0] ?? 'web';
            }
    }
