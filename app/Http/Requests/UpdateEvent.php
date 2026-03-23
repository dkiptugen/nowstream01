<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvent extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  $this->user('admin')->can('edit_event');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_name' => ['required'],
            'event_description' => ['nullable'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'stream_thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'ticket.type.*' => ['nullable', 'string', 'max:255'],
            'ticket.quantity.*' => ['nullable', 'integer', 'min:0'],
            'ticket.currency.*' => ['nullable', 'string', 'size:3'],
            'ticket.cost.*' => ['nullable', 'numeric', 'min:0'],
            'stream.rate_name.*' => ['nullable', 'string', 'max:255'],
            'stream.currency.*' => ['nullable', 'string', 'size:3'],
            'stream.price.*' => ['nullable', 'numeric', 'min:0'],
            'merch.name.*' => ['nullable', 'string', 'max:255'],
            'merch.currency.*' => ['nullable', 'string', 'size:3'],
            'merch.price.*' => ['nullable', 'numeric', 'min:0'],
            'merch.image.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'merch.variants.*.name.*' => ['nullable', 'string', 'max:255'],
            'merch.variants.*.price_override.*' => ['nullable', 'numeric', 'min:0'],
            'merch.variants.*.stock_total.*' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
