<?php
	
	namespace App\Jobs;
	
	use App\Libs\DPO;
	use App\Models\Event;
	use App\Models\Transaction;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Log;
	
	class VerifyTokenJob implements ShouldQueue
		{
			use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
		
		/**
		 * @var \App\Libs\DPO
		 */
			public DPO   $dpo;
			public mixed $transactionToken;
			public       $tries      = 5;
			public       $retryAfter = 5;
		
		/**
		 * Create a new job instance.
		 */
			public function __construct ($transactionToken)
				{
					$this->dpo              = new DPO();
					$this->transactionToken = $transactionToken;
				}
		
		/**
		 * Execute the job.
		 */
			public function handle ()
			: void
				{
					try
						{
							//dd($request->all ());
							$trans = Transaction::with (['subscription'])->where ('transaction_token',
							                                                      $this->transactionToken)->first ();
							//	Log::info ($trans);
							if (!is_null ($trans))
								{
									if ($trans->subscription->status != 1)
										{
											$this->dpo->transaction_token = $this->transactionToken;
											$this->dpo->company_token     = config ('custom.DPO.DPO_COMPANY_TOKEN');
											$this->dpo->accountref        = $trans->identifier;
											$statusResult                 = $this->dpo->verifyToken ();
											$statusCode                   = simplexml_load_string ($statusResult);
											Log::info ($statusCode);
											if ($statusCode->Result == '000')
												{
													try
														{
															$trans->amount_paid = $statusCode->TransactionAmount;
															$trans->receipt     = (string) $statusCode->TransactionApproval ?? '';
															$trans->date_paid   = Carbon::parse ($statusCode->TransactionSettlementDate)->toDateTimeString ();
															$trans->response    = json_encode ($statusCode);
															$res                = $trans->save ();
															if ($res)
																{
																	if ($statusCode->TransactionAmount == $trans->subscription->balance)
																		{
																			$trans->subscription ()->update ([
																				                                 'status' => 1, 'balance' => ($trans->balance - $statusCode->TransactionAmount), 'amount_paid' => $statusCode->TransactionAmount
																			                                 ]);
																			Log::info ($trans->subscription);
																		}
																}
															
														}
													catch (\Exception $e)
														{
															Log::error ($e->getMessage ());
														}
													$this->delete ();
												}
											else
												{
													if ($this->attempts () < $this->tries)
														{
															$this->release ($this->retryAfter);
														}
													else
														{
															Log::error ("Job failed after maximum retries.");
															$this->delete ();
														}
												}
											
										}
									
								}
							else
								{
									Log::error ("Transaction not found.");
									$this->delete ();
								}
						}
					catch (\Exception $e)
						{
							Log::error ($e->getMessage ());
						}
				}
		}
