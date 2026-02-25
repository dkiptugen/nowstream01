<?php
	
	
	namespace App\Http\Controllers\Callbacks;
	
	use App\Events\DPOSuccessEvent;
	use App\Http\Controllers\Controller;
	use App\Http\Resources\SubscriptionResource;
	use App\Libs\AfricasTalking;
	use App\Libs\DPO;
	use App\Models\Event;
	use App\Models\Subscription;
	use App\Models\Transaction;
	use App\Notifications\StreamKeyNotification;
	use Exception;
	use Illuminate\Http\Request;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Log;
	
	
	class DPOCallbackController extends Controller
		{
			public    $dpo;
			protected $transrenewals;
			
			public function __construct ()
				{
					
					parent::__construct ();
					$this->dpo = new DPO();
					
				}
			
			public function verifyToken (Request $request)
				{
					
				
				
				//Log::error ("Verify token: \n \n".json_encode ($request));
					try
						{
							//dd($request->all ());
							$trans = Transaction::where ('transaction_token',
							                                                      $request->TransactionToken)
							                    ->first ();
						//	Log::info ($trans);
							if (!is_null ($trans))
								{
									$subscription = Subscription::find($trans->subscription_id);
									$this->dpo->transaction_token = $trans->transaction_token;
									$this->dpo->company_token     = config ('custom.DPO.DPO_COMPANY_TOKEN');
									$this->dpo->accountref        = $trans->identifier;
									$statusResult                 = $this->dpo->verifyToken ();
									$statusCode                   = simplexml_load_string ($statusResult);
									//Log::debug($statusCode);
									if ($statusCode->Result == '000')
										{
											try
												{
													if($trans->subscription->status !=1)
														{
															$trans->amount_paid = $statusCode->TransactionAmount;
															$trans->receipt   = (string) $statusCode->TransactionApproval ?? '';
															$trans->date_paid = Carbon::parse ($statusCode->TransactionSettlementDate)->toDateTimeString ();
															$trans->response  = json_encode ($statusCode);
															$res              = $trans->save ();
															if ($res)
																{
																	
																	$subscription->status = 1;
																	$subscription->balance= ($trans->balance - $statusCode->TransactionAmount);
																	$subscription->amount_paid= $statusCode->TransactionAmount;
																	$result = $subscription->save();
																	if($result)
																		{
																			if(!$this->isBazeEmail($subscription->user->email))
																				{
																					$subscription->user->notify(new  StreamKeyNotification
																					                            ($subscription->user,$subscription));
																				}
																			else
																				{
																					$at       = new AfricasTalking();
																					$phoneNew = '0' . substr($this->removeSpaces
																						($subscription->user->phone), -9);
																					$at->send_sms(
																						'baze',
																						$phoneNew,
																						'Welcome to Somali Nite Live Event. Your Streaming Key is: '.$subscription->stream_token.' Click
https://streamer.co.ke/somalinite to watch the Event.'
																					);
																				}
																			return redirect ()->route ('success', $subscription->event_id);
																		}
																	
																	
																}
															if(!$this->isBazeEmail($subscription->user->email))
																{
																	$subscription->user->notify(new  StreamKeyNotification
																	                            ($subscription->user,$subscription));
																}
															else
																{
																	$at       = new AfricasTalking();
																	$phoneNew = '0' . substr($this->removeSpaces
																		($subscription->user->phone), -9);
																	$at->send_sms(
																		'baze',
																		$phoneNew,								
																		'Welcome to Somali Nite Live Event. Your Streaming Key is: '.$subscription->stream_token.' Click
https://streamer.co.ke/somalinite to watch the Event.'
																	);
																}
															return redirect ()->route ('success', $subscription->event_id);
														}
													else
														{
															if(!$this->isBazeEmail($subscription->user->email))
																{
																	$subscription->user->notify(new  StreamKeyNotification
																	                            ($subscription->user,$subscription));
																}
															else
																{
																	$at       = new AfricasTalking();
																	$phoneNew = '0' . substr($this->removeSpaces
																		($subscription->user->phone), -9);
																	$at->send_sms(
																		'baze',
																		$phoneNew,
																										'Welcome to Somali Nite Live Event. Your Streaming Key is: '.$subscription->stream_token.' Click
https://streamer.co.ke/somalinite to watch the Event.'
																	);
																}
														
															return redirect ()->route ('success', $subscription->event_id);
														}
												
												}
											catch (Exception $e)
												{
													return redirect ()->back()->with(['errors'=> $e->getMessage ()]);
												
												}
										}
									else
										{
											return redirect ()->back()->with(['errors'=> 'payment not successful']);
										
										}
									
								}
							else
								{
									return redirect ()->back()->with(['errors'=>'transaction not found']);
								
								}
						}
					catch (Exception $e)
						{
							return redirect ()->back()->with(['errors'=> $e->getMessage ()]);
						}
				
					
				}
			
			
		}