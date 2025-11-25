<?php
	
	namespace App\Libs;
	
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\Http;
	use Illuminate\Support\Str;
	
	class SafaricomContent
		{
			private $response;
			
			public function __construct ()
				{
					$this->response = new \stdClass();
				}
			
			public function generate_token ()
				{
					try
						{
							
							
							$header = ['Content-Type' => 'application/x-www-form-urlencoded'];
							$req    = $response = Http::withHeaders ($header)->withBasicAuth (config ('custom.SAFARICOM_CONTENT.SAFARICOM_CONTENT_USERNAME'),
							                                                                  config ('custom.SAFARICOM_CONTENT.SAFARICOM_CONTENT_PASSWORD'))->post (config ('custom.SAFARICOM_CONTENT.SAFARICOM_CONTENT_TOKEN_URL').'?grant_type=client_credentials');
							if ($req->successful ())
								{
									$this->response->status = 1;
									$this->response->data   = $req->object ();
									return $this->response;
								}
							$this->response->status = 0;
							$this->response->data   = $req->body ();
							return $this->response;
						}
					catch (\Exception $exception)
						{
							$this->response->status = 0;
							$this->response->data   = $exception->getMessage ();
							return $this->response;
						}
				}
			
			public function query_active_subscription ($msisdn)
				{
					try
						{
							if (!Cache::has ('safaricom_content_token'))
								{
									$tk = $this->generate_token ();
									if ($tk->status == 1)
										{
											Cache::put ('safaricom_content_token', $tk->data->access_token,
											            now ()->addSeconds ($tk->data->expires_in));
										}
									else
										{
											return $tk->data;
										}
								}
							$bearertoken = Cache::get ('safaricom_content_token');
							
							$headers = [
								'X-Source-CountryCode' => 'KE', 'X-Source-Operator' => 'mysafaricom', 'X-Source-Division' => 'DE', 'X-Source-System' => 'web-portal', 'X-Source-Timestamp' => time (), 'X-Correlation-ConversationID' => '86575528-53c5-4f67-936e-ec21078ee5eb', 'X-MessageID' => '09199780-cf40-4a3b-afb1-2bb639341a92', 'X-DeviceInfo' => 'cc44db4c-662b-40a2-90ca-c04ff11ccaec', 'X-DeviceId' => 'cdvasc0bvnbda3', 'X-DeviceToken' => '234567', 'X-MSISDN' => substr ($msisdn,
								                                                                                                                                                                                                                                                                                                                                                                                                                                                         -9), 'X-App' => 'web-portal', 'X-Version' => '1', 'x-api-key' => 'OWqwCWWp1w9FlWBWUOnOv5F5hLmWDdQs7rvS9IsS',
							];
							
							$req = Http::withToken ($bearertoken,
							                        'Bearer')->withHeaders ($headers)->get (config ('custom.SAFARICOM_CONTENT.SAFARICOM_CONTENT_QUERY_SUB_URL'),
							                                                                [
								                                                                'cspid' => config ('custom.SAFARICOM_CONTENT.SAFARICOM_CONTENT_CSPID'), 'encryption' => '',
							                                                                ]);
							//dd($req->body ());
							
							if ($req->successful ())
								{
									$this->response->status = 1;
									$this->response->data   = $req->object ();
									return $this->response;
								}
							
							$this->response->status  = 0;
							$this->response->data    = $req->body ();
							$this->response->headers = $req->headers ();
							return $this->response;
						}
					catch (\Exception $exception)
						{
							$this->response->status = 0;
							$this->response->data   = $exception->getMessage ();
							return $this->response;
						}
					
				}
			
			public function products ($productid)
				{
					$stream = false;
					$video  = false;
					$data   = false;
					switch ($productid)
						{
						case 'somali0002':
								$stream                 = true;
								$video                  = true;
								$data                   = true;
								$cost                   = 1500;
								$reserved_currency_cost = 25;
								break;
						case 'somali0003':
								$stream                 = true;
								$cost                   = 1500;
								$reserved_currency_cost = 25;
								break;
						case 'somali0004':
								$video                  = true;
								$cost                   = 1500;
								$reserved_currency_cost = 25;
								break;
						}
					return (object) ['stream' => $stream, 'video' => $video, 'data' => $data, 'cost' => $cost, 'reserved_currency_cost' => $reserved_currency_cost];
				}
		}