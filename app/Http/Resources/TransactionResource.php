<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'payment_method'=>$this->payment_method,
			'receipt'=>$this->receipt,
			'amount_paid'=>$this->amount_paid,
			'currency'=>$this->currency,
			'transaction_token'=>$this->transaction_token,
			'date_paid'=>Carbon::parse ($this->date_paid)->toDateTimeString (),
        ];
    }
}
