<?php


	namespace App\Http\Controllers\Frontend;

	use App\Events\PaymentFailed;
	use App\Http\Controllers\Controller;
	use App\Jobs\VerifyTokenJob;
	use App\Libs\DPO;
	use App\Libs\Mpesa;
	use App\Models\Event;
	use App\Models\Product;
	use App\Models\Region;
	use App\Models\Subscription;
	use App\Models\Transaction;
    use App\Traits\Meta;
    use Exception;
	use Illuminate\Database\Eloquent\ModelNotFoundException;
	use Illuminate\Http\Request;
	use Illuminate\Support\Carbon;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Log;
	use Mgcodeur\CurrencyConverter\Facades\CurrencyConverter;

	class SubscriptionController extends Controller
		{
            use Meta;
			public function subscribe (Request $request)
				{
					$request->validate ([
						                    'event_id' => 'required|uuid|exists:events,uuid',
						                    'rate_id' => 'required|integer|exists:products,id',
						                    'payment_method_id' => 'required|integer|in:1,2'
					                    ]);

					DB::beginTransaction ();

					try
						{
							$event = Event::where('uuid', $request->event_id)->firstOrFail();
							$eventRate = Product::query()
								->whereKey($request->rate_id)
								->where('payable_id', $event->uuid)
								->where('payable_type', Event::class)
								->where('type', 'ticket')
								->where('is_active', 1)
								->first();

							if (is_null ($eventRate))
								{
									return redirect ()->back ()->with ('error', 'Event rate not found');
								}

							$cost         = $eventRate->price;
							$channelId    = optional($event->streams()->first())->channel_id;
							$subscription = Subscription::where ('user_id', $request->user ()->id)
							                            ->where ('event_id', $request->event_id)
							                            ->where ('event_rate_id', $eventRate->id)
							                            ->first ();


							switch ($request->payment_method_id)
								{
								case 1:
										$paymentmethod = 'mpesa';
										break;
								case 2:
										$paymentmethod = 'dpo';
										break;
								case 3:
										$paymentmethod = 'Safaricom Content';
										break;
								}
							$country = $request->country ?? session('country', 'KE');
							$currency = 'KES';
							if ($country != 'KE')
								{
									if ($paymentmethod == 'dpo')
										{
											$cost     = $eventRate->price;
											$currency = 'USD';
										}
								}
							if (is_null ($subscription) || ($subscription->status==0 && $subscription->currency!=$currency))
								{
									$mpesaTransactionId = $this->identifer ('Subscription', 'identifier', 10);
									$subscription       = Subscription::create ([
										                                            'user_id' => $request->user ()->id,
										                                            'identifier' => $mpesaTransactionId,
										                                            'type' => 'ticket',
										                                            'currency' => $currency,
										                                            'cost' => $cost,
										                                            'amount_paid' => 0,
										                                            'balance' => $cost,
										                                            'status' => 0,
										                                            'event_rate_id' => $request->rate_id,
										                                            'event_id' => $request->event_id,
										                                            'channel_id' => $channelId,
										                                            'stream_token' => uniqid ()
									                                            ]);


								}
							else
								{
									if ($subscription->balance == 0)
										{
											$subscription->status = 1;
											$ss                   = $subscription->save ();
											if ($ss)
												{
													return redirect ()->route ('success', ['eventId' => $subscription->event_id]);
												}
										}
									if ($subscription->cost != $cost)
										{
											$balance = $cost - $subscription->amount_paid;
											$subscription->update ([
												                       'cost' => $cost, 'balance' => $balance
											                       ]);
										}


								}


							DB::commit ();

							// Redirect based on the payment method
							if ($request->payment_method_id == 1)
								{
									return redirect ()->route ('mpesa', ['id' => $subscription->getKey ()]);
								}
							elseif ($request->payment_method_id == 2)
								{
									return redirect ()->route ('dpo', ['id' => $subscription->getKey ()]);
								}
							else
								{
									return redirect ()->route ('', ['id' => $subscription->id]);
								}
						}
					catch (Exception $e)
						{
							DB::rollBack ();
							Log::error ('Subscription failed:', ['exception' => $e->getMessage ()]);
							return redirect ()->back ()->with ('error', 'Subscription failed: '.$e->getMessage ());
						}
				}


			public function succeed ($eventId)
				{
					$event = Event::where('uuid', $eventId)->firstOrFail();
					$ticket = null;

					if (Auth::check())
						{
							$ticket = \App\Models\Ticket::where('user_id', Auth::id())
								->where('event_id', $event->uuid)
								->latest()
								->first();

							if (!$ticket)
								{
									$subscription = Subscription::where('user_id', Auth::id())
										->where('event_id', $event->uuid)
										->where('status', 1)
										->latest()
										->first();

									if ($subscription)
										{
											$rate = Product::find($subscription->event_rate_id);
											$ticket = \App\Models\Ticket::create([
												'user_id' => Auth::id(),
												'event_id' => $event->uuid,
												'type' => optional($rate)->name ?? 'Standard',
												'price' => $subscription->cost,
											]);
										}
								}
						}

					return view ('Frontend.modules.payments.successful', compact ('event', 'ticket'));
				}
				public function succeess ()
					{
						return view ('Frontend.modules.payments.successful');
					}

			public function mpesa ($id)
				{
					$subscription = Subscription::findOrFail ($id);
					if(	$subscription->status ==1)
						{
							return redirect ()->route ('success', ['eventId' => $subscription->event_id]);
						}
					return view ('Frontend.modules.payments.mpesa', compact ('subscription'));
				}


			public function mpesaStk (Request $request)
				{
					try
						{
							$sub = Subscription::whereIdentifier ($request->identifier)->firstOrFail ();
							if (!is_null ($sub))
								{
									$transaction = Transaction::find ($sub->latest_transaction_id);
									if (is_null ($transaction) || $transaction->currency == 'USD')
										{
											if ($sub->currency == 'USD')
												{
													$eventRate = Product::find ($sub->event_rate_id);
													$sub       = Subscription::create ([
														                                   'user_id' => Auth::user ()->id, 'identifier' => $this->identifer ('Subscription',
														                                                                                                     'identifier',
														                                                                                                     10), 'type' => 'ticket', 'currency' => "KES", 'cost' => $eventRate->price, 'amount_paid' => 0, 'balance' => $eventRate->price, 'status' => 0, 'event_rate_id' => $eventRate->id, 'event_id' => $sub->event_id, 'channel_id' => $sub->channel_id, 'stream_token' => uniqid ()
													                                   ]);
												}
											$transaction                  = new Transaction();
											$transaction->payment_method  = 'mpesa';
											$transaction->cost            = $sub->balance;
											$transaction->amount_paid     = 0;
											$transaction->currency        = $sub->currency;
											$transaction->event_id        = $sub->event_id;
											$transaction->channel_id      = $sub->channel_id;
											$transaction->subscription_id = $sub->id;
											$transaction->user_id         = Auth::user ()->id;
											$transaction->save ();


										}
									$mpesa                 = new Mpesa('production');
									$mpesa->shortcode      = config ('custom.MPESA.MPESA_SHORTCODE');
									$mpesa->passkey        = config ('custom.MPESA.MPESA_PASS_KEY');
									$mpesa->consumerkey    = config ('custom.MPESA.MPESA_CONSUMER_KEY');
									$mpesa->consumersecret = config ('custom.MPESA.MPESA_CONSUMER_SECRET');
									$mpesa->type           = 'Paybill';
									$mpesa->msisdn         = "254".substr ($this->removeSpaces ($request->msisdn), -9);
									$mpesa->amount         = (int) ceil ($sub->balance);
									$mpesa->ref            = $sub->identifier;
									$mpesa->stk_callback   =  route('mpesa.stk_push_request', ['subscription'=>$sub->identifier]);
									$mpesa->desc           = 'payment for streaming service';
									//Log::info(json_encode($mpesa));
									$check                 = $mpesa->stkpush ();


									$transaction->response = $check;
									$transaction->save ();
									$sub->latest_transaction_id = $transaction->id;
									$sub->save ();
								}
							if (isset($check->errorCode))
								{
									event (new PaymentFailed($sub->identifier, ["message" => $check->errorMessage]));
									Log::error ('Mpesa Stk: '.$check->errorMessage);
									return redirect ()->back ()->with ('error',
									                                   'Encountered an error sending stk!!. Contact Customer Care for assistance ');
								}
							else
								{
									return redirect ()->back ()->with ('success', 'Enter your Pin to complete payment');
								}
						}
					catch (ModelNotFoundException $e)
						{
							Log::error ($e->getMessage ());
							return redirect ()->back ()->with ('error', 'Subscription not found');
						}
					catch (Exception $e)
						{
							Log::error ($e->getMessage ());
							return redirect ()->back ()->with ('error', 'Encountered an error');
						}
				}

			public function dpo ($id)
				{
					try
						{
							$subscription = Subscription::findOrFail ($id);
							if(	$subscription->status ==1)
								{
									return redirect ()->route ('success', ['eventId' => $subscription->event_id]);
								}
							$transaction  = Transaction::find ($subscription->latest_transaction_id);
							if (is_null ($transaction))
								{
									$transaction                  = new Transaction();
									$transaction->payment_method  = 'dpo';
									$transaction->cost            = $subscription->balance;
									$transaction->name            = Auth::user ()->name;
									$transaction->amount_paid     = 0;
									$transaction->currency        = $subscription->currency;
									$transaction->event_id        = $subscription->event_id;
									$transaction->channel_id      = $subscription->channel_id;
									$transaction->subscription_id = $subscription->id;
									$transaction->user_id         = $subscription->user_id;
									$transaction->save ();
								}

							if (!is_null ($transaction))
								{
									$subscription->latest_transaction_id = $transaction->id;
									$subscription->save ();
									$dpo                            = new DPO();
									$dpo->company_token             = config ('custom.DPO.DPO_COMPANY_TOKEN');
									$dpo->amount                    = $subscription->balance;
									$dpo->email                     = Auth::user ()->email;
									$dpo->currency                  = $subscription->currency;
									$dpo->accountref                = $subscription->identifier;
									$dpo->back_url                  = route ('event.pay', [
										$subscription->event_id, $subscription->event_rate_id
									]);
									$dpo->redirect_url              = route ('dpo.verify_token');
									$dpo->service[0]["type"]        = config ('custom.DPO.DPO_SERVICE_CODE');
									$dpo->service[0]["description"] = 'payment for streaming service ';
									$dpo->service[0]["ref"]         = $subscription->identifier;
									$dpo->service[0]["date"]        = Carbon::now ();
									$checkout                       = (object) $dpo->Checkout ();
									Log::error (json_encode ($checkout));
									// Handle the response from DPO and update the transaction
									if ($checkout->status)
										{

											$transaction->transaction_token = $checkout->token;
											$transaction->response          = $checkout;
											$transaction->save ();
										}
							VerifyTokenJob::dispatch ((string)$transaction->transaction_token)
									              ->delay(now ()->addMinutes (3))
									              ->onQueue ('high');

									return view ('Frontend.modules.payments.dpo',
									             compact ('subscription', 'transaction', 'checkout'));
								}
						}
					catch (ModelNotFoundException $e)
						{
							Log::error ($e->getMessage ());
						}
					catch (Exception $e)
						{
							Log::error ($e->getMessage ().' '.$e->getTraceAsString ());
						}

				}

			public function saf_content ($id)
				{

			}
		}
