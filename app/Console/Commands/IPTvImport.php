<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Content;
use App\Models\Language;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IPTvImport extends Command
    {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'import:iptv';

    /**
     * The console command description.
     *
     * @var string
     */
        protected        $description = 'Command description';
        protected string $baseUrl     = 'https://iptv-org.github.io/api';

    /**
     * Execute the console command.
     */
        public function handle()
            {
                try
                    {


                        $channels = Http::timeout(120)
                                        ->get($this->baseUrl . '/channels.json')
                                        ->json();


                        foreach ($channels as $channel)

                            {
                                if (!is_null($channel['id']))
                                    {
                                        $region = Region::where('code', $channel['country'])->first();
                                        $language         = Language::where('code', $region->language_code)
                                                                    ->orWhere('name', $region->language)
                                                                    ->first();

                                        $content = Content::where('old_id', $channel['id'])
                                                          ->where('source', 'iptv_org')
                                                          ->first();

                                        if (is_null($content))
                                            {
                                                $content                 = new Content();
                                                $content->title          = $channel['name'];
                                                $content->old_id         = $channel['id'];
                                                $content->source         = 'iptv_org';
                                                $content->region_id      = $region->id??0;
                                                $content->description    = $channel['description'] ?? '';
                                                $content->country        = $region->name??'undefined';;
                                                $content->author         = substr(implode(',',$channel['owners'] ?? []),0,250);
                                                $content->content_group  = 'tv';
                                                $content->type           =  'application/x-mpegURL';
                                                $content->status         = 0;
                                                $content->system_user_id = 1;
                                                $content->language       = $language->code??'undefined';
                                                $content->language_id    = $language->id??0;
                                                $content->genre          = json_encode($channel['categories'] ?? []);
                                                $res                     = $content->save();
                                                if ($res)
                                                    {
                                                        foreach ($channel['categories'] ?? [] as $cat)
                                                            {

                                                                $category = Category::firstOrCreate(
                                                                    ['slug' => Str::slug($cat)],
                                                                    ['name' => $cat]
                                                                );
                                                                $content->categories()->syncWithoutDetaching($category->uuid);
                                                            }
                                                        $this->info($channel['name'] . ' imported successfully');
                                                    }
                                            }
                                    }
                                else
                                    {
                                        $this->error('No channels found');
                                    }
                            }
                        $this->call('import:tvstreams');
                        $this->call('import:tvlogos');
                        $this->info('Import completed successfully');
                    }
                catch (\Exception $e)
                    {
                        $this->error($e->getMessage().$e->getLine());
                    }
            }
    }
