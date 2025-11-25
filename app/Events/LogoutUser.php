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
	
	class LogoutUser implements ShouldBroadcastNow
		{
			use Dispatchable, InteractsWithSockets, SerializesModels;
			
			public $user_id;
		
		/**
		 * Create a new event instance.
		 */
			public function __construct ($userId)
				{
					$this->user_id = $userId;
				}
			
			public function broadcastOn()
				{
					return new Channel('login.' .$this->user_id);
				}
			
			public function broadcastAs()
				{
					return 'new_login';
				}
			
			public function broadcastWith()
				{
					return ['status'=>true] ;
				}
		}
