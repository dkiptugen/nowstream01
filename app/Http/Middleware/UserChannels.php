<?php

	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Symfony\Component\HttpFoundation\Response;

	class UserChannels
		{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
		 */
			public function handle (Request $request, Closure $next)
			: Response
				{

					if (Auth::check ('admin'))
						{

							if (!Cache::has ('user_channels_'.$request->user ()->id))
								{
									if (!is_null ($request->user ()->products))
										{
											Cache::put ('user_channels_'.$request->user ()->id,
												$request->user ()->channels);
										}
								}

						}
					return $next($request);
				}
		}
