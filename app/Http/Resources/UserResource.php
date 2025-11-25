<?php

namespace App\Http\Resources;

use App\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	use Helper;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'name' => $this->name,
			'email' => $this->email,
			'phone' => $this->encrypt ($this->phone,config ('custom.APP.ENCRYPTION_KEY'),config ('custom.APP.ENCRYPTION_SALT')),
			'watch_history' => $this->watch_history,
        ];
    }
}
