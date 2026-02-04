<?php

	namespace App\Http\Controllers\API;

	use App\Http\Resources\SubscriptionResource;
	use App\Models\Subscription;
	use App\Models\SystemUser;
	use App\Models\User;
	use App\Traits\Helper;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Validator;

	class APIController
		{
			use Helper;

			public function login (Request $request)
				{
					$validator = Validator::make ($request->all (), [
						'email' => 'required|string|email|max:255', 'password' => 'required|string|min:6',
					]);
					if ($validator->fails ())
						{
							return response ([
								'errors' => $validator->errors ()->all ()
							], 422);
						}
					$user = SystemUser::where ('email', $request->email)->first ();
					if ($user)
						{
							if (Hash::check ($request->password, $user->password))
								{
									if ($user->status == 1)
										{
											$token = $user->createToken ('authToken');


											$response = ['token' => $token->plainTextToken];

											return response ()->json (["status"  => "True", "responseCode" => 200,
											                           "message" => "User ", "data" => $response
											]);

										}
									return response ()->json (["status"  => "False", "responseCode" => 422,
									                           "message" => "User not active", "data" => []
									]);
								}
							else
								{
									return response ()->json (["status"  => "False", "responseCode" => 422,
									                           "message" => "Password mismatch", "data" => []
									]);
								}
						}
					else
						{

							return response ()->json (["status"  => "False", "responseCode" => 422,
							                           "message" => "User does not exist", "data" => []
							]);
						}
				}
			public function decrypt_msisdn (Request $request)
				{
					if (!request()->has('msisdn') || request('msisdn') == '')
						{
							return response()->json([
								                        'status' => false,
								                        'error'  => 'Msisdn is required'
							                        ]);
						}

					$decrypted = $this->decrypt ($request->msisdn,config('custom.APP.ENCRYPTION_KEY'),config('custom.APP.ENCRYPTION_SALT'));
					//dd($decrypted);
					if(is_null($decrypted))
						{
							return response ()->json (["status"  => "True", "responseCode" => 200,
							                           "date" =>$decrypted
							]);
						}
					return response ()->json (["status"  => "False", "responseCode" => 422,
					                           "message" => "Invalid encryption key and salt"
					]);
				}
			public function check_user_subscriptions(Request $request)
				{
					if (!request()->hasAny(['email','msisdn','account']))
						{
							return response()->json([
								                        'status' => false,
								                        'error'  => 'Email or MSISDN or account is required'
							                        ]);
						}
					if($request->has('account') && !is_null($request->account))
						{
							return $this->check_specific_subscription ($request->account);
						}

					$user = User::where('phone',$request->msisdn)
								->orWhere('email',$request->email)
								->first();
					if(!is_null($user))
						{
							$subscription = Subscription::with(['transactions','user','event','user.watchHistory'])
							                            ->where('user_id',$user->id)
														->orderBy('updated_at','desc')
								                        ->paginate(10,'*','page',$request->page??1);
							if(!empty($subscription))
								{
									return response ()->json (["status"  => True, "responseCode" => 200,
									                           "message" => "Subscriptions  found", "data"=> SubscriptionResource::collection($subscription)
									                          ]);
								}
							return response ()->json (["status"  => False, "responseCode" => 422,
							                           "message" => "subscriptions not found"
							                          ]);
						}
					return response ()->json (["status"  => False, "responseCode" => 422,
					                           "message" => "user not found"
					                          ]);
				}
			public function check_specific_subscription($account_id)
				{
					$subscription = Subscription::with(['transactions','user','event','user.watchHistory'])
					                            ->where('identifier',$account_id)
					                            ->first();
					if(!is_null($subscription))
						{
							return response ()->json (["status"  => True, "responseCode" => 200,
							                           "message" => "Subscriptions  found", "data"=> new SubscriptionResource($subscription)
							                          ]);
						}
					return response ()->json (["status"  => False, "responseCode" => 422,
					                           "message" => "subscriptions not found"
					                          ]);
				}
			public function cancel_subscription($account_id)
				{
					$subscription = Subscription::where('identifier',$account_id)
					                            ->first();
					if(!is_null($subscription))
						{
							$subscription->status = 2;
							$subscription->amount_paid = 0;
							$subscription->balance = $subscription->cost;
							$subscription->save();


							return response ()->json (["status"  => True, "responseCode" => 200,
							                           "message" => "Content token cancelled"
							                          ]);
						}
					return response ()->json (["status"  => False, "responseCode" => 422,
					                           "message" => "subscriptions not found"
					                          ]);
				}

		}
