<?php
	
	namespace App\Macros;
	
	use Illuminate\Http\Response;
	
	class ResponseMacros
		{
			public static function register()
				{
					Response::macro('api', function ($data, $message=null, $status = 200) {
						return response()->json([
							                        "data"    => $data,
							                        "status"  => $status,
							                        "message" => $message
						                        ]);
					});
				}
		}