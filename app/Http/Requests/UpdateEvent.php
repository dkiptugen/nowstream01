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
	             'event_name' =>['Required'],
	             "description"=>["nullable"],
	             //"thumbnail" => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'],
             ];
       
    }
}
