<?php
	
	namespace App\Http\Middleware;
	
	use Closure;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class CheckAppKey
		{
		/**
		 * Handle an incoming request.
		 *
		 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
		 */
			public function handle (Request $request, Closure $next)
			: Response
				{
					$appkey = $request->header ('appkey');
					
					if (is_null ($appkey))
						{
							return response ()->json (["status" => false, "responseCode" => 422, "message" => " Unauthorized action "],
							                          401);
						}
					else
						{
							$key = env ('API_KEY');
							if ($key !== $appkey)
								{
									return response ()->json (["status" => false, "responseCode" => 423, "message" => "Invalid token "],
									                          401);
								}
						}
					return $next($request);
				}
		}
