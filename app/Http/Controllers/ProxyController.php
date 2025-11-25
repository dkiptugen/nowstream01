<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Http;
	
	class ProxyController extends Controller
		{
			public function check (Request $request)
				{
					$url = 'https://live.baze.co.ke/dpo/check';
					
					$query = $request->query ();
					
					$response = Http::withHeaders ([
						                               'x-requested-with' => 'XMLHttpRequest',
					                               ])->get ($url, $query);
					
					return response ($response->body (), $response->status ())
						->header ('Content-Type',$response->header ('Content-Type'))
					;
				}
		}
