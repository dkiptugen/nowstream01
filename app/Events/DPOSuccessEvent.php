<?php
	
	namespace App\Events;
	
	use Illuminate\Broadcasting\Channel;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Broadcasting\PresenceChannel;
	use Illuminate\Broadcasting\PrivateChannel;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
	use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Support\Facades\Log;
	
	class DPOSuccessEvent implements ShouldBroadcastNow
		{
			use Dispatchable, InteractsWithSockets, SerializesModels;
		
		/**
		 * Create a new event instance.
		 */
			public $subscription;
			
			
			public function __construct ($subscription)
				{
					$this->subscription = $subscription;
				}
			
			
			public function broadcastOn ()
				{
					return new Channel('login.'.$this->subscription->user);
				}
			
			public function broadcastAs ()
				{
					return 'new_payment';
				}
			
			public function broadcastWith ()
				{
					$stream = $this->subscription->event->streams;
					Log::error($stream);
					return redirect ()->route ('success', $stream->event_id);
				}
		}
