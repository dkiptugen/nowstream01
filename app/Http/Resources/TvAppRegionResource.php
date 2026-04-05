<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvAppRegionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'capital' => $this->capital,
            'currency' => $this->currency,
            'currency_code' => $this->currency_code,
            'currency_symbol' => $this->currency_symbol,
            'language' => $this->language,
            'language_code' => $this->language_code,
            'flag' => $this->flag,
            'contents_count' => isset($this->contents_count) ? (int) $this->contents_count : null,
        ];
    }
}
