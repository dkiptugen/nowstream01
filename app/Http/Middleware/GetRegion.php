<?php

	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Http\Request;

	use Illuminate\Support\Facades\View;
    use Stevebauman\Location\Facades\Location;

    class GetRegion
		{
		/**
		 * Handle an incoming request.
		 *
		 * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
		 */
			public function handle(Request $request, Closure $next)
				{
					$ip = $request->ip();
					$position = Location::get($ip);
					//dd($request->ip());
					/*if ($position)
						{
							$country=$position->countryCode;
							$request->merge([
								                'country' => $position->countryCode,
							                ]);
						}
					else
						{*/
							$country="unkown";
							$request->merge([
								                'country' => 'Unknown'
							                ]);
						//}
					View::share('country', $country);

					// Share the parameter globally to all routes
					app()->singleton('country', function () use ($country) {
						return $country;
					});
					return $next($request);
				}
		}
