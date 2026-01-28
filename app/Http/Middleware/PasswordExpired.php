<?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Symfony\Component\HttpFoundation\Response;

    class PasswordExpired
        {
        /**
         * Handle an incoming request.
         *
         * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
         */
            public function handle(Request $request, Closure $next)
            : Response
                {
                    $user                = $request->user('admin');
                    $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

                    if (Carbon::now()->diffInDays($password_changed_at) >= (int)config('custom.AUTHENTICATION.PASSWORD_EXPIRY'))
                        {
                            return redirect()->route('password.expired');
                        }

                    return $next($request);
                }
        }
