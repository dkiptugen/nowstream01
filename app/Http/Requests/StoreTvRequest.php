<?php

    namespace App\Http\Requests;

    use App\Models\Category;
    use App\Models\Language;
    use Illuminate\Contracts\Validation\ValidationRule;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    use Intervention\Image\Drivers\Gd\Driver;
    use Intervention\Image\ImageManager;

    class StoreTvRequest extends FormRequest
        {
        /**
         * Authorization
         */
            public function authorize()
            : bool
                {
                    return $this->user()?->can('create_tv') ?? false;
                }

        /**
         * Prepare input before validation
         */
            protected function prepareForValidation()
            : void
                {
                    $genre = $this->input('genre');

                    // Handle Tagify JSON or comma-separated input
                    if (is_string($genre))
                        {
                            $decoded = json_decode($genre, true);

                            if (json_last_error() === JSON_ERROR_NONE)
                                {
                                    $genre = collect($decoded)->pluck('value')->toArray();
                                }
                            else
                                {
                                    $genre = explode(',', $genre);
                                }
                        }

                    $this->merge([
                                     'genre' => collect($genre ?? [])
                                         ->map(fn($genre) => trim(strtolower($genre)))
                                         ->filter()
                                         ->unique()
                                         ->values()
                                         ->toArray(),
                                 ]);
                }

        /**
         * Validation rules
         */
            public function rules()
            : array
                {
                    return [
                        'title'       => ['required', 'string', 'max:255'],
                        'description' => ['nullable', 'string'],

                        'thumbnail' => [
                            'required',
                            'image',
                            'mimes:jpeg,png,jpg,webp',
                            'max:2048'
                        ],

                        'region_id' => ['required', 'integer', 'exists:regions,id'],
                        'category_id' => ['required','uuid','exists:categories,uuid'],
                        'genre'   => ['nullable', 'array'],
                        'genre.*' => ['string', 'max:50'],
                        'language_id' => ['required', 'integer', 'exists:languages,id'],

                        'stream_url' => ['required', 'url']
                    ];
                }

        /**
         * Transform validated data
         */
            public function validated($key = null, $default = null)
                {
                    $data = parent::validated();

                    $disk    = config('filesystems.default');
                    $manager = new ImageManager(new Driver());

                    /*
                    |--------------------------------------------------------------------------
                    | Thumbnail Processing
                    |--------------------------------------------------------------------------
                    */

                    if ($this->hasFile('thumbnail'))
                        {

                            $image = $manager->read($this->file('thumbnail')->getRealPath());

                            $image->scaleDown(width: 512, height: 512);

                            $path = "nowstream/tv/logo/" . Str::uuid() . ".webp";

                            Storage::disk($disk)->put(
                                $path,
                                (string)$image->toWebp(90),
                                ['visibility' => 'public']
                            );

                            $data['thumbnail_url'] =  Storage::disk($disk)->url($path);
                        }
                    unset($data['thumbnail']);
                    /*
                    |--------------------------------------------------------------------------
                    | Additional Metadata
                    |--------------------------------------------------------------------------
                    */

                    $data['category']       = Category::where('uuid', $this->category_id)->value('name');
                    $data['language']       = Language::where('id', $this->language_id)->value('code');
                    $data['content_group']  = 'tv';
                    $data['system_user_id'] = $this->user()->id;
                    $data['status']         = 1;

                    return $data;
                }
        }
