<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

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
                        'logo'           => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
                        'cover'          => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
                        'banner'         => ['image', 'mimes:jpg,png,jpeg,webp', 'max:2048', 'dimensions:min_width=100,min_height=100'],
                        'social_links'   => ['nullable', 'array'],
                        'social_links.*' => ['nullable', 'url', 'starts_with:https://'],
                        'status'         => 'nullable',
                        'keywords'       => 'nullable'
                    ];
                }

            public function validated($key = null, $default = null)
                {

                    $data = parent::validated();

                    $tenantPath = 'tenant/' . Str::slug($data['name']);

                    if ($this->hasFile('logo'))
                        {
                            $filename = Str::uuid() . '.' . $this->file('logo')->extension();
                            $path     = $tenantPath . '/logo/' . $filename;
                            Storage::disk(config('filesystems.default'))->put($path, file_get_contents($this->file('logo')->getRealPath()));
                            $data['logo'] = $path; // store path only
                            // ---- CREATE FAVICON ----
                            $manager = new ImageManager(new Driver());

                            $image = $manager->read($this->file('logo')->getRealPath());

                            // Resize logo proportionally (max 400px)
                            $image->scaleDown(width: 400, height: 400);

                            // Create 512x512 transparent canvas
                            $canvas = $manager->create(512, 512);

                            // Place logo at center
                            $canvas->place($image, 'center');

                            $faviconName = 'favicon-' . Str::uuid() . '.png';
                            $faviconPath = $tenantPath . '/favicon/' . $faviconName;

                            Storage::disk(config('filesystems.default'))->put(
                                $faviconPath,
                                (string)$canvas->toPng()
                            );

                            $data['favicon'] = $faviconPath;
                        }
                    else
                        {
                            // Log::error("logo not found");
                        }

                    if ($this->hasFile('cover'))
                        {
                            $filename      = Str::uuid() . '.' . $this->file('cover')->extension();
                            $path     = $tenantPath . '/cover/' . $filename;
                            Storage::disk(config('filesystems.default'))->put($path, file_get_contents($this->file('cover')->getRealPath()));
                            $data['cover'] = $path;
                        }

                    if ($this->hasFile('banner'))
                        {
                            $filename       = Str::uuid() . '.' . $this->file('banner')->extension();
                            $path     = $tenantPath . '/banner/' . $filename;
                            Storage::disk(config('filesystems.default'))->put($path, file_get_contents($this->file('banner')->getRealPath()));
                            $data['banner'] = $path;
                        }
                    $data['system_user_id'] = $this->user()->id;
                    $data['status']         = 1;
                    //Log::info('validated',$data);
                    return $data;
                }
        }
