<?php

    namespace App\Console\Commands;

    use App\Models\Category;
    use App\Models\Content;
    use App\Models\Language;
    use App\Models\Region;
    use App\Traits\Meta;
    use Illuminate\Console\Command;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Str;

    class ImportRadio extends Command
        {
            use Meta;

        /**
         * The name and signature of the console command.
         *
         * @var string
         */
            protected $signature = 'import:radio';


        /**
         * The console command description.
         *
         * @var string
         */
            protected $description = 'Command description';

        /**
         * Execute the console command.
         */
            public function handle()
                {
                    $data   = file_get_contents(__DIR__ . "/radio.json");
                    $radios = json_decode($data);
                    foreach ($radios as $radio)
                        {
                            $region = Region::where('name', $radio->region)
                                            ->first();
                            if (!is_null($region))
                                {
                                    $language = Language::where('code', $region->language_code)
                                                        ->orWhere('name', $region->language)
                                                        ->first();
                                }
                            else
                                {
                                    $language = new Language();

                                }
                            $ext  = strtolower(pathinfo($radio->stream_link, PATHINFO_EXTENSION));
                            $type = match ($ext)
                                {
                                'mp3'   => 'audio/mpeg',
                                'm4a'   => 'audio/mp4',
                                'aac'   => 'audio/aac',
                                'wav'   => 'audio/wav',
                                'ogg'   => 'audio/ogg',
                                'm3u8'  => 'application/vnd.apple.mpegurl',
                                default => 'application/octet-stream',
                                };


                            $pod                 = Content::firstOrNew(['source' => 'Songaplay', 'content_group' => 'radio', 'old_id' => Str::slug($radio->name)]);
                            $pod->title          = $radio->name;
                            $pod->description    = $this->remove_emoji($radio->description);
                            $pod->stream_url     = $radio->stream_link;
                            $pod->author         = 'Caydeesoft';
                            $pod->source         = 'Radio Directory';
                            $pod->publishdate    = Carbon::now();
                            $pod->status         = $radio->status;
                            $pod->language_id    = $language->id ?? 0;
                            $pod->language       = $language->code ?? 'undefined';
                            $pod->country        = $region->name ?? 'undefined';
                            $pod->thumbnail_url  = $radio->thumbnail;
                            $pod->content_group  = 'radio';
                            $pod->region_id      = $region->id ?? 0;
                            $pod->system_user_id = 1;
                            $pod->type           = $type;
                            $pod->genre          = json_encode(explode(',', $radio->categories));
                            $res                 = $pod->save();
                            if ($res)
                                {
                                    foreach (explode(',',$radio->categories) as $cat)
                                        {
                                            $category = Category::firstOrCreate(['name' => $cat], ['type'=> 'radio', 'system_user_id' => 1, 'slug' => Str::slug($cat)]);
                                            $pod->categories()->syncWithoutDetaching($category->uuid);
                                        }
                                    $this->info($radio->name . ' imported');
                                }
                        }
                    $this->info('Import completed successfully');
                }
        }
