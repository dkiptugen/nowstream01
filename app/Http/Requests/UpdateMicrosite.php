<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Illuminate\Validation\Rule;
    use Intervention\Image\Drivers\Gd\Driver;
    //use Intervention\Image\Image;
    use Intervention\Image\ImageManager;
    use Intervention\Image\ImageManagerStatic as Image;

    class UpdateMicrosite extends FormRequest
        {
        /**
         * Determine if the user is authorized to make this request.
         */
            public function authorize()
            : bool
                {
                    return $this->user()?->can('edit_microsite') ?? false;
                }

            protected function prepareForValidation()
                {
                    $this->merge([
                                     'social_links' => collect(explode(',', $this->input('social_links') ?? ''))
                                         ->map(fn($url) => trim($url))
                                         ->filter()
                                         ->values()
                                         ->toArray(),
                                     'keywords'     => collect(explode(',', $this->input('keywords') ?? ''))
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
                        'name'           => ['required', Rule::unique('microsites', 'name')
                                                             ->ignore($this->microsite->id)],
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

                    $tenantPath = 'nowstream/tenant/' . Str::slug($data['name']);

                    // ----------- LOGO & FAVICON -----------
                    if ($this->hasFile('logo')) {
                        $file = $this->file('logo');

                        // --- 1. Logo WebP ---
                        $logoImage = Image::make($file->getRealPath());
                        $logoImage->resize(512, 512, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $logoFilename = Str::uuid() . '.webp';
                        $logoPath = $tenantPath . '/logo/' . $logoFilename;

                        Storage::disk(config('filesystems.default'))
                               ->put($logoPath, (string) $logoImage->encode('webp', 90));

                        $data['logo'] = $logoPath;

                        // --- 2. Favicon 512x512 transparent ---
                        $faviconCanvas = Image::canvas(512, 512);

                        $faviconLogo = Image::make($file->getRealPath());
                        $faviconLogo->resize(400, 400, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $faviconCanvas->insert($faviconLogo, 'center');

                        $faviconFilename = 'favicon-' . Str::uuid() . '.png';
                        $faviconPath = $tenantPath . '/favicon/' . $faviconFilename;

                        Storage::disk(config('filesystems.default'))
                               ->put($faviconPath, (string) $faviconCanvas->encode('png'));

                        $data['favicon'] = $faviconPath;
                    }

                    // ----------- COVER -----------
                    if ($this->hasFile('cover')) {
                        $coverFile = $this->file('cover');
                        $coverImage = Image::make($coverFile->getRealPath());
                        $coverImage->resize(1200, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $coverFilename = Str::uuid() . '.webp';
                        $coverPath = $tenantPath . '/cover/' . $coverFilename;

                        Storage::disk(config('filesystems.default'))
                               ->put($coverPath, (string) $coverImage->encode('webp', 90));

                        $data['cover'] = $coverPath;
                    }

                    // ----------- BANNER -----------
                    if ($this->hasFile('banner')) {
                        $bannerFile = $this->file('banner');
                        $bannerImage = Image::make($bannerFile->getRealPath());
                        $bannerImage->resize(1200, 400, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $bannerFilename = Str::uuid() . '.webp';
                        $bannerPath = $tenantPath . '/banner/' . $bannerFilename;

                        Storage::disk(config('filesystems.default'))
                               ->put($bannerPath, (string) $bannerImage->encode('webp', 90));

                        $data['banner'] = $bannerPath;
                    }
                    $data['system_user_id'] = $this->user()->id;
                    $data['status']         = $data['status'] ?? 0;
                    //Log::info('validated',$data);
                    return $data;
                }
        }
