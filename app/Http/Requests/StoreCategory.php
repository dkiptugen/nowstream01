<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user('admin')->can('create_category');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cat_name'=>['required','unique:categories,name'],
            'description'=> 'nullable',
            'list_order' => ['nullable','numeric'],
            'status' => ['nullable','numeric'],
            'parent_id' => ['nullable','numeric']

        ];
    }
}
