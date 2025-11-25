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
			public $subscription;


			public function __construct($subscription)
				{
					$this->subscription = $subscription;
				}


			public function broadcastOn()
				{
					return new Channel('payment.'.$this->subscription->identifier);
				}

			public function broadcastAs()
				{
					return 'new_payment';
				}
			public function broadcastWith() {
				return [
					'check' => $this->subscription->status,
					'balance' =>$this->subscription->balance
				];
			}

		}
