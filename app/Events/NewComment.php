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

class NewComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
     public $comment;
    /**
     * Create a new event instance.
     */
    public function __construct($comment)
	    {
	        $this->comment =$comment;
	    }
		
		public function broadcastOn()
			{
				$className = preg_replace('/^.*\\\/', '',$this->comment->commentable_type);
				$className = strtolower($className);
				return new Channel($className.'_comment.' .$this->comment->commentable_id);
			}
		
		public function broadcastAs()
			{
				return 'new_comment';
			}
		
		public function broadcastWith()
			{
				$comment =  preg_replace([
					                          '/https?:\/\/\S+/', // Remove URLs
					                          '/\b\d{10,13}\b/' // Remove phone numbers
				                          ], '',$this->comment->comment);
				return [
					'user_img'  =>$this->comment->user->image?? asset('avatar.png'),
					'user_name' =>$this->comment->user->name,
					'comment'   =>$comment,
					'comment_time'=>$this->comment->created_at->diffForHumans()
				] ;
			}
}
