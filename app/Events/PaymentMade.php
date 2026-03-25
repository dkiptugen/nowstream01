<?php

	namespace App\Events;

	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Facades\Log;


	class PaymentMade implements ShouldBroadcastNow
		{
			use Dispatchable, InteractsWithSockets, SerializesModels;


		/**
		 * Create a new event instance.
		 */
			public $payment;


			public function __construct($payment)
				{
					$this->payment = $payment;
				}


			public function broadcastOn()
				{
					return new Channel('payment.'.$this->identifier());
				}

			public function broadcastAs()
				{
					return 'new_payment';
				}
			public function broadcastWith() {
				return [
					'check' => $this->isPaid(),
					'balance' => $this->balance()
				];
			}

			private function identifier()
				{
					return $this->payment->identifier
						?? $this->payment->order_number
						?? $this->payment->subscription_token;
				}

			private function isPaid()
				{
					if (isset($this->payment->status))
						{
							return (int) $this->payment->status === 1;
						}

					return ($this->payment->payment_status ?? null) === 'paid';
				}

			private function balance()
				{
					if (isset($this->payment->balance))
						{
							return $this->payment->balance;
						}

					return 0;
				}

		}
