<?php
	
	namespace App\Http\Controllers\Callbacks;
	
	use App\Events\PaymentFailed;
	use App\Http\Controllers\Controller;
	use App\Jobs\MpesaPaymentJob;
	use App\Jobs\OrderPaymentJob;
	use App\Models\Order;
	use App\Models\Subscription;
	use App\Models\Transaction;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Log;
	use Illuminate\Support\Facades\Schema;
	
	class MpesaCallbackController extends Controller
		{
			public function b2b (Request $request)
				{
					$callbackJSONData                    = file_get_contents ('php://input');
					$callbackData                        = json_decode ($callbackJSONData)->Result;
					$resultCode                          = $callbackData->ResultCode;
					$resultDesc                          = $callbackData->ResultDesc;
					$originatorConversationID            = $callbackData->OriginatorConversationID;
					$conversationID                      = $callbackData->ConversationID;
					$transactionID                       = $callbackData->TransactionID;
					$transactionReceipt                  = $callbackData->ResultParameters->ResultParameter[0]->Value;
					$transactionAmount                   = $callbackData->ResultParameters->ResultParameter[1]->Value;
					$b2CWorkingAccountAvailableFunds     = $callbackData->ResultParameters->ResultParameter[2]->Value;
					$b2CUtilityAccountAvailableFunds     = $callbackData->ResultParameters->ResultParameter[3]->Value;
					$transactionCompletedDateTime        = $callbackData->ResultParameters->ResultParameter[4]->Value;
					$receiverPartyPublicName             = $callbackData->ResultParameters->ResultParameter[5]->Value;
					$B2CChargesPaidAccountAvailableFunds = $callbackData->ResultParameters->ResultParameter[6]->Value;
					$B2CRecipientIsRegisteredCustomer    = $callbackData->ResultParameters->ResultParameter[7]->Value;
					
					
				}
			
			public function b2c (Request $request)
				{
					//$request->createFromGlobals();
					$callbackJSONData                 = file_get_contents ('php://input');
					$callbackData                     = json_decode ($callbackJSONData);
					$resultCode                       = $callbackData->Result->ResultCode;
					$resultDesc                       = $callbackData->Result->ResultDesc;
					$originatorConversationID         = $callbackData->Result->OriginatorConversationID;
					$conversationID                   = $callbackData->Result->ConversationID;
					$transactionID                    = $callbackData->Result->TransactionID;
					$initiatorAccountCurrentBalance   = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
					$debitAccountCurrentBalance       = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
					$amount                           = $callbackData->Result->ResultParameters->ResultParameter[2]->Value;
					$debitPartyAffectedAccountBalance = $callbackData->Result->ResultParameters->ResultParameter[3]->Value;
					$transCompletedTime               = $callbackData->Result->ResultParameters->ResultParameter[4]->Value;
					$debitPartyCharges                = $callbackData->Result->ResultParameters->ResultParameter[5]->Value;
					$receiverPartyPublicName          = $callbackData->Result->ResultParameters->ResultParameter[6]->Value;
					$currency                         = $callbackData->Result->ResultParameters->ResultParameter[7]->Value;
					
					
				}
			
			public function validation (Request $request)
				{
					
					
					$callbackJSONData  = file_get_contents ('php://input');
					$callbackData      = json_decode ($callbackJSONData);
					$transactionType   = optional ($callbackData)->TransactionType;
					$transID           = optional ($callbackData)->TransID;
					$transTime         = optional ($callbackData)->TransTime;
					$transAmount       = optional ($callbackData)->TransAmount;
					$businessShortCode = optional ($callbackData)->BusinessShortCode;
					$billRefNumber     = optional ($callbackData)->BillRefNumber;
					$invoiceNumber     = optional ($callbackData)->InvoiceNumber;
					$orgAccountBalance = optional ($callbackData)->OrgAccountBalance;
					$thirdPartyTransID = optional ($callbackData)->ThirdPartyTransID;
					$MSISDN            = optional ($callbackData)->MSISDN;
					$firstName         = optional ($callbackData)->FirstName;
					$middleName        = optional ($callbackData)->MiddleName;
					$lastName          = optional ($callbackData)->LastName;
					
					//Log::error ('Validation: '.$callbackJSONData);
					$sub = Schema::hasTable('subscriptions')
						? Subscription::where ('identifier', $billRefNumber)->first ()
						: null;
					$order = Order::where('order_number', $billRefNumber)->first();
					//Log::info('validation :',(array)$sub);
					//Log::info($trans);
					if (!is_null ($sub))
						{
							if ((int) $sub->balance < (int) $transAmount)
								{
									event (new PaymentFailed($sub->identifier, ["message" => "Invalid Amount"]));
									return response ()->json ([
										                          "ResultCode" => "C2B00013",
										                          "ResultDesc" => "Invalid Amount"
									                          ]
									);
								}
							if ($sub->status == 1)
								{
									event (new PaymentFailed($sub->identifier,
									                         ["message" => "Payment blocked due to double payment"]
									       )
									);
									return response ()->json ([
										                          "ResultCode" => "C2B00016",
										                          "ResultDesc" => "The account has already been charged"
									                          ]
									);
								}
							return response ()->json ([
								                          "ResultCode" => "0",
								                          "ResultDesc" => "Accepted"
							                          ]
							);
						}
					elseif (!is_null($order))
						{
							if ((float) $order->total_amount !== (float) $transAmount)
								{
									event (new PaymentFailed($order->order_number, ["message" => "Invalid Amount"]));
									return response ()->json ([
										                          "ResultCode" => "C2B00013",
										                          "ResultDesc" => "Invalid Amount"
									                          ]
									);
								}

							if ($order->payment_status === 'paid')
								{
									event (new PaymentFailed($order->order_number,
									                         ["message" => "Payment blocked due to double payment"]
									       )
									);
									return response ()->json ([
										                          "ResultCode" => "C2B00016",
										                          "ResultDesc" => "The account has already been charged"
									                          ]
									);
								}

							return response ()->json ([
								                          "ResultCode" => "0",
								                          "ResultDesc" => "Accepted"
							                          ]
							);
						}
					else
						{
							event (new PaymentFailed($billRefNumber, ["message" => "Invalid Account Number"]));
							return response ()->json ([
								                          "ResultCode" => "C2B00012",
								                          "ResultDesc" => "Invalid Account Number"
							                          ]
							);
						}
					
				}
			
			public function confirmation (Request $request)
				{
					//Log::error('first'.$request->getContent());
					
					$callbackJSONData  = $request->getContent ();
					$callbackData      = json_decode ($callbackJSONData);
					$transactionType   = optional ($callbackData)->TransactionType;
					$transID           = optional ($callbackData)->TransID;
					$transTime         = optional ($callbackData)->TransTime;
					$transAmount       = optional ($callbackData)->TransAmount;
					$businessShortCode = optional ($callbackData)->BusinessShortCode;
					$billRefNumber     = optional ($callbackData)->BillRefNumber;
					$invoiceNumber     = optional ($callbackData)->InvoiceNumber;
					$orgAccountBalance = optional ($callbackData)->OrgAccountBalance;
					$thirdPartyTransID = optional ($callbackData)->ThirdPartyTransID;
					$MSISDN            = optional ($callbackData)->MSISDN;
					$firstName         = optional ($callbackData)->FirstName;
					$middleName        = optional ($callbackData)->MiddleName;
					$lastName          = optional ($callbackData)->LastName;
					$subscription = Schema::hasTable('subscriptions')
						? Subscription::where ('identifier', $billRefNumber)->first ()
						: null;
					$order = Order::where('order_number', $billRefNumber)->first();
					if (!is_null ($subscription))
						{
							$name = trim ($firstName." ".$middleName." ".$lastName);
							MpesaPaymentJob::dispatch ($billRefNumber, $transAmount, $transID, $name, $MSISDN,
							                           $transTime, $callbackJSONData
							)->onQueue ('high');
						}
					elseif (!is_null($order))
						{
							$name = trim ($firstName." ".$middleName." ".$lastName);
							OrderPaymentJob::dispatch($billRefNumber, $transAmount, $transID, $name, $MSISDN,
								$transTime, $callbackJSONData
							)->onQueue('high');
						}
					
					
					return response ()->json ([
						                          
						                          "ResultCode" => 0,
						                          "ResultDesc" => "Success"
					                          ]
					);
					
					
				}
			
			public function account_balance (Request $request)
				{
					$callbackJSONData         = file_get_contents ('php://input');
					$callbackData             = json_decode ($callbackJSONData);
					$resultType               = $callbackData->Result->ResultType;
					$resultCode               = $callbackData->Result->ResultCode;
					$resultDesc               = $callbackData->Result->ResultDesc;
					$originatorConversationID = $callbackData->Result->OriginatorConversationID;
					$conversationID           = $callbackData->Result->ConversationID;
					$transactionID            = $callbackData->Result->TransactionID;
					$accountBalance           = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
					$BOCompletedTime          = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
				}
			
			public function reversal (Request $request)
				{
					
					$callbackJSONData = file_get_contents ('php://input');
					
					$callbackData             = json_decode ($callbackJSONData);
					$resultType               = $callbackData->Result->ResultType;
					$resultCode               = $callbackData->Result->ResultCode;
					$resultDesc               = $callbackData->Result->ResultDesc;
					$originatorConversationID = $callbackData->Result->OriginatorConversationID;
					$conversationID           = $callbackData->Result->ConversationID;
					$transactionID            = $callbackData->Result->TransactionID;
					
					
				}
			
			public function stk_push_request (Request $request,$subscription)
				{
				
					$callbackJSONData = $request->getContent ();
					Log::error (file_get_contents ('php://input'));
					$data = json_decode ($callbackJSONData);
					
					if (isset($data->Body) && is_object ($data->Body))
						{
							$callbackData       = $data->Body;
							$resultCode         = $callbackData->stkCallback->ResultCode;
							$resultDesc         = $callbackData->stkCallback->ResultDesc;
							$merchantRequestID  = $callbackData->stkCallback->MerchantRequestID;
							$checkoutRequestID  = $callbackData->stkCallback->CheckoutRequestID;
							Log::error ($resultCode." : ".$resultDesc);
							if(isset($callbackData->stkCallback->CallbackMetadata) && is_object( $callbackData->stkCallback->CallbackMetadata))
								{
									$amount             = $callbackData->stkCallback->CallbackMetadata->Item[0]->Value;
									$mpesaReceiptNumber = $callbackData->stkCallback->CallbackMetadata->Item[1]->Value;
									$balance            = optional($callbackData->stkCallback->CallbackMetadata->Item[2])->Value;
									$transactionDate    = $callbackData->stkCallback->CallbackMetadata->Item[3]->Value;
									$phoneNumber        = $callbackData->stkCallback->CallbackMetadata->Item[4]->Value;
								}
						
							if($resultCode != "0")
								{
									event (new PaymentFailed($subscription, ["message" =>	$resultDesc ]));
								}
						
						
						}
					
				}
			
			public function stk_push_query (Request $request)
				{
					$callbackJSONData = file_get_contents ('php://input');
					Log::info ('stk'.$callbackJSONData);
					$callbackData        = json_decode ($callbackJSONData);
					$responseCode        = $callbackData->ResponseCode;
					$responseDescription = $callbackData->ResponseDescription;
					$merchantRequestID   = $callbackData->MerchantRequestID;
					$checkoutRequestID   = $callbackData->CheckoutRequestID;
					$resultCode          = $callbackData->ResultCode;
					$resultDesc          = $callbackData->ResultDesc;
					
					
				}
			
			public function transaction_status (Request $request)
				{
					$callbackJSONData         = file_get_contents ('php://input');
					$callbackData             = json_decode ($callbackJSONData);
					$resultCode               = $callbackData->Result->ResultCode;
					$resultDesc               = $callbackData->Result->ResultDesc;
					$originatorConversationID = $callbackData->Result->OriginatorConversationID;
					$conversationID           = $callbackData->Result->ConversationID;
					$transactionID            = $callbackData->Result->TransactionID;
					$ReceiptNo                = $callbackData->Result->ResultParameters->ResultParameter[0]->Value;
					$ConversationID           = $callbackData->Result->ResultParameters->ResultParameter[1]->Value;
					$FinalisedTime            = $callbackData->Result->ResultParameters->ResultParameter[2]->Value;
					$Amount                   = $callbackData->Result->ResultParameters->ResultParameter[3]->Value;
					$TransactionStatus        = $callbackData->Result->ResultParameters->ResultParameter[4]->Value;
					$ReasonType               = $callbackData->Result->ResultParameters->ResultParameter[5]->Value;
					$TransactionReason        = $callbackData->Result->ResultParameters->ResultParameter[6]->Value;
					$DebitPartyCharges        = $callbackData->Result->ResultParameters->ResultParameter[7]->Value;
					$DebitAccountType         = $callbackData->Result->ResultParameters->ResultParameter[8]->Value;
					$InitiatedTime            = $callbackData->Result->ResultParameters->ResultParameter[9]->Value;
					$OriginatorConversationID = $callbackData->Result->ResultParameters->ResultParameter[10]->Value;
					$CreditPartyName          = $callbackData->Result->ResultParameters->ResultParameter[11]->Value;
					$DebitPartyName           = $callbackData->Result->ResultParameters->ResultParameter[12]->Value;
					
				}
			
		}
