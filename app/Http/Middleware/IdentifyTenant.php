<?php

    namespace App\Http\Middleware;

    use Closure;
    use App\Models\Microsite;

    class IdentifyTenant
        {
            public function handle($request, Closure $next)
                {
                    $host = $request->getHost();
                    $baseDomain = config('app.base_domain');
                    // Ignore main domain
                    if (in_array($host, [$baseDomain, 'www.'.$baseDomain]))
                    {
                        return $next($request);
                    }

                    // Extract subdomain
                    $subdomain = str_replace('.'.$baseDomain, '', $host);
                    dd($subdomain) ;
                    $tenant = Microsite::where('domain', $subdomain)
                                       ->where('status', 1)
                                       ->first();

                    if (!$tenant)
                    {
                        return redirect()->route('home');
                    }

                    // Make tenant globally available
                    app()->instance('tenant', $tenant);

                    return $next($request);
                }
        }
