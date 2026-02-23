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
                                     'keywords' => collect(explode(',', $this->input('keywords') ?? ''))
                                         ->map(fn($keyword) => trim($keyword))
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
                    $data       = parent::validated();
                    $disk       = config('filesystems.default');
                    $tenantPath = "nowstream/tenant/" . Str::slug($data['name']);

                    // 1. Initialize Manager with a specific driver (v3 requirement)
                    $manager = new ImageManager(new Driver());

                    // ----------- LOGO & FAVICON -----------
                    if ($this->hasFile('logo'))
                        {
                            $logoFile = $this->file('logo');

                            // --- 1. Logo WebP ---
                            // read() replaces make()
                            $logoImage = $manager->read($logoFile->getRealPath());

                            // scaleDown() handles both aspectRatio and upsize constraints automatically
                            $logoImage->scaleDown(width: 512, height: 512);

                            $logoPath = "$tenantPath/logo/" . Str::uuid() . ".webp";

                            // toWebp() returns an EncodedImage object; use (string) or ->toString()
                            Storage::disk($disk)->put($logoPath,
                                                      $logoImage->toWebp(90)->toString(),
                                                      ['visibility' => 'public']);
                            $data['logo'] = $logoPath;

                            // --- 2. Favicon (512x512) ---
                            // We re-read the file to ensure we start from the source, not the resized logo
                            $faviconImage = $manager->read($logoFile->getRealPath());

                            // pad() is the new way to fit an image into a canvas with a background (transparent by default)
                            $faviconImage->pad(512, 512);

                            $faviconPath = "$tenantPath/favicon/favicon-" . Str::uuid() . ".png";
                            Storage::disk($disk)->put($faviconPath,
                                                      $faviconImage->toPng()->toString(),
                                                      ['visibility' => 'public']);

                            $data['favicon'] = $faviconPath;
                        }

                    // ----------- COVER & BANNER -----------
                    // Using a loop to keep things DRY (Don't Repeat Yourself)
                    $uploads = [
                        'cover'  => ['w' => 1200, 'h' => 600],
                        'banner' => ['w' => 1200, 'h' => 400],
                    ];

                    foreach ($uploads as $field => $dims)
                        {
                            if ($this->hasFile($field))
                                {
                                    $image = $manager->read($this->file($field)->getRealPath());

                                    $image->scaleDown(width: $dims['w'], height: $dims['h']);

                                    $path = "$tenantPath/$field/" . Str::uuid() . ".webp";
                                    Storage::disk($disk)->put($path,
                                                              $image->toWebp(90)->toString(),
                                                              ['visibility' => 'public']);

                                    $data[$field] = $path;
                                }
                        }

                    // ----------- SYSTEM INFO -----------
                    $data['system_user_id'] = $this->user()->id;
                    $data['status']         = 1;

                    return $data;
                }
        }
