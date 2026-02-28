<?php
	
	namespace App\Jobs;
	
	use App\Events\PaymentMade;
	use App\Libs\AfricasTalking;
	use App\Models\Subscription;
	use App\Models\Transaction;
	use App\Notifications\StreamKeyNotification;
	use App\Traits\Helper;
	use Exception;
	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Database\Eloquent\ModelNotFoundException;
	use Illuminate\Foundation\Bus\Dispatchable;
	use Illuminate\Queue\InteractsWithQueue;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Log;
	
	class MpesaPaymentJob implements ShouldQueue
		{
			use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Helper;
			
			public          $subscription;
			public          $amount;
			protected mixed $receipt;
			protected mixed $name;
			protected mixed $msisdn;
			protected mixed $transtime;
			protected mixed $response;
		
		/**
		 * Create a new job instance.
		 */
			public function __construct ($subscription, $amount, $receipt, $name, $msisdn, $transtime, $response)
				{
					$this->subscription = $subscription;
					$this->amount       = $amount;
					$this->receipt      = $receipt;
					$this->name         = $name;
					$this->msisdn       = $msisdn;
					$this->transtime    = $transtime;
					$this->response     = $response;
					
				}
			function isBazeEmail($email)
				{
					// Regular expression to match email addresses from baze.co.ke
					$pattern = '/^[a-zA-Z0-9._%+-]+@live\.baze\.co\.ke$/';
					return preg_match($pattern, $email) === 1;
				}
			function removeSpaces ($input)
				{
					$result = preg_replace('/[\s-]+/', '', $input);
					
					return $result;
				}
		/**
		 * Execute the job.
		 */
			public function handle ()
			: void
				{
					try
						{
							
							
							$sub = Subscription::where ('identifier', $this->subscription)->firstOrFail ();
							
							$transaction = Transaction::find ($sub->latest_transaction_id);
							if (!is_null ($transaction))
								{
									$transaction->name        = $this->name;
									$transaction->msisdn      = $this->msisdn;
									$transaction->receipt     = $this->receipt;
									$transaction->amount_paid = $this->amount;
									$transaction->date_paid   = Carbon::parse ($this->transtime);
									$transaction->save ();
								}
							else
								{
									$transaction                  = new Transaction();
									$transaction->payment_method  = 'mpesa';
									$transaction->cost            = $sub->balance;
									$transaction->name            = $this->name;
									$transaction->msisdn          = $this->msisdn;
									$transaction->receipt         = $this->receipt;
									$transaction->amount_paid     = $this->amount;
									$transaction->date_paid       = Carbon::parse ($this->transtime);
									$transaction->currency        = $sub->currency;
									$transaction->event_id        = $sub->event_id;
									$transaction->channel_id      = $sub->channel_id;
									$transaction->subscription_id = $sub->id;
									$transaction->user_id         = $sub->user_id;
									$save                         = $transaction->save ();
								}
							
							if ($sub->balance <= $this->amount)
								{
									Log::info ('more or equal');
									$sub->status = 1;
								}
							else
								{
									$trans                  = new Transaction();
									$trans->payment_method  = 'mpesa';
									$trans->cost            = $sub->amount_paid - $this->amount;
									$trans->amount_paid     = 0;
									$trans->currency        = $sub->currency;
									$trans->event_id        = $sub->event_id;
									$trans->channel_id      = $sub->channel_id;
									$trans->subscription_id = $sub->id;
									$trans->user_id         = $sub->user_id;
									$save                   = $trans->save ();
									if ($save)
										{
											$sub->latest_transaction_id = $trans->id;
										}
									
									Log::info ('less ');
								}
							$sub->increment ('amount_paid', $this->amount);
							$sub->decrement ('balance', $this->amount);
							
							$res = $sub->save ();
							if ($res)
								{
									$check = Subscription::find ($sub->id);
									if(!$this->isBazeEmail($check->user->email))
										{
											$check->user->notify(new  StreamKeyNotification
											                            ($check->user,$check));
										}
									else
										{
											$at       = new AfricasTalking();
											$phoneNew = '0' . substr($this->removeSpaces
												($check->user->phone), -9);
											$at->send_sms(
												'baze',
												$phoneNew,
												'Welcome to Somali Nite Live Event. Your Streaming Key is: '.$check->stream_token.' Click
https://streamer.co.ke/somalinite to watch the Event.'
											);
										}
									event (new PaymentMade($check));
								}
						}
					catch (ModelNotFoundException $e)
						{
							// Handle the specific exception when a model is not found
							Log::error ('Model not found: '.$e->getMessage ());
						}
					catch (Exception $e)
						{
							// Handle any other exceptions
							Log::error ('An error occurred: '.$e->getMessage ());
						}
				}
		}
