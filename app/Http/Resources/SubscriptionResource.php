<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
	        'event'=>$this->event->event_name,
			'subscription_key'=>$this->identifier,
			'stream_key'=>$this->stream_token,
			'paid'=>(bool)$this->status,
			'cost'=>$this->cost,
			'created_at'=>Carbon::parse ($this->created_at)->toDateTimeString (),
			'transaction'=>TransactionResource::collection ($this->transactions),
	        'user'=> new UserResource($this->user),
			
        ];
    }
}
