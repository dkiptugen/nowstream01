<?php

    namespace App\Http\Middleware;

    use Closure;
    use App\Models\Microsite;

    class IdentifyTenant
        {
            public function handle($request, Closure $next)
                {
                    $host       = $request->getHost();
                    $baseDomain = config('app.base_domain');
                    // Ignore main domain
                    if (in_array($host, [$baseDomain, 'www.' . $baseDomain]))
                        {
                            return $next($request);
                        }
                    $tenant = Microsite::where('domain', $host)
                                       ->where('status', 1)
                                       ->first();

                    if (!$tenant)
                        {
                            return redirect()->route('home');
                        }

                    // Make tenant globally available
                    app()->instance('tenant', collect($tenant)->only(['name','logo']));

                    return $next($request);
                }
        }
