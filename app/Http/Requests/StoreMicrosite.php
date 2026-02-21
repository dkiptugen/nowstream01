<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    class StoreMicrosite extends FormRequest
        {
        /**
         * Determine if the user is authorized to make this request.
         */
            public function authorize()
            : bool
                {
                    return $this->user()?->can('create_microsite') ?? false;
                }

            protected function prepareForValidation()
                {
                    $this->merge([
                                     'social_links' => collect(explode(',', $this->input('social_links') ?? ''))
                                         ->map(fn($url) => trim($url))
                                         ->filter()
                                         ->values()
                                         ->toArray(),
                                 ]);
                }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
         */
            public function rules()
            : array
                {
                    return [
                        'name'           => ['required', 'unique:microsites,name'],
                        'description'    => ['required', 'string'],
                        'colorscheme'    => ['nullable', 'array'],
                        'logo'           => ['image', 'mimes:jpg,png,jpeg,webp','max:2048','dimensions:min_width=100,min_height=100'],
                        'cover'          => ['image', 'mimes:jpg,png,jpeg,webp','max:2048','dimensions:min_width=100,min_height=100'],
                        'banner'         => ['image', 'mimes:jpg,png,jpeg,webp','max:2048','dimensions:min_width=100,min_height=100'],
                        'social_links'   => ['nullable', 'array'],
                        'social_links.*' => ['nullable', 'url', 'starts_with:https://'],
                    ];
                }

            public function validated($key = null, $default = null)
                {

                    $data = parent::validated();

                    $tenantPath = 'tenant/' . Str::slug($data['name']);

                    if ($this->hasFile('logo')) {
                        $filename = Str::uuid() . '.' . $this->file('logo')->extension();
                        $path = $this->file('logo')->storeAs($tenantPath . '/logo', $filename, 'public');
                        $data['logo'] = $path; // store path only
                    }

                    if ($this->hasFile('cover')) {
                        $filename = Str::uuid() . '.' . $this->file('cover')->extension();
                        $path = $this->file('cover')->storeAs($tenantPath . '/cover', $filename, 'public');
                        $data['cover'] = $path;
                    }

                    if ($this->hasFile('banner')) {
                        $filename = Str::uuid() . '.' . $this->file('banner')->extension();
                        $path = $this->file('banner')->storeAs($tenantPath . '/banner', $filename, 'public');
                        $data['banner'] = $path;
                    }
                    $data['system_user_id'] = $this->user()->id;

                    return $data;
                }
        }
