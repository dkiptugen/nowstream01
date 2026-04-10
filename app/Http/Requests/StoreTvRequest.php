<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\ValidationRule;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreTvRequest extends FormRequest
        {
        /**
         * Determine if the user is authorized to make this request.
         */
            public function authorize()
            : bool
                {
                    return $this->user()->can('create_tv');
                }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, ValidationRule|array<mixed>|string>
         */
            public function rules()
            : array
                {
                    return [
                        'title'       => ['required'],
                        'description' => ['nullable'],
                        'thumbnail'   => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                        'country'     => ['required'],
                        'language'    => ['required'],
                        'stream_url'  => ['required', 'url']
                    ];
                }
        }
