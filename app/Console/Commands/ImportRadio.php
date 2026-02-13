<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\Language;
use App\Models\Region;
use App\Traits\Meta;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
    protected $base_url   = 'https://www.songaplay.com/engine/api/';

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
        $data       =   file_get_contents(__DIR__."/radio.json");
        $radios    =   json_decode($data);
        foreach ($radios as $radio)
            {
                $region = Region::where('name', $radio->region)
                                ->first();
                $language = Language::where('code', $region->language_code)
                                    ->orWhere('name', $region->language)
                                    ->first();
                $pod                 = Content::firstOrNew([ 'source' => 'Songaplay', 'content_group' => 'radio', 'old_id' => Str::slug($radio->name)]);
                $pod->title          = $radio->name;
                $pod->description    = $this->remove_emoji($radio->description);
                $pod->stream_url     = $radio->stream_link;;
                $pod->author         = 'songaplay';
                $pod->source         = 'Songaplay';
                $pod->publishdate    = Carbon::now();
                $pod->status         = $radio->status;
                $pod->language_id    = $language->id ?? 0;
                $pod->language       = $language->code??'';
                $pod->country        = $region->name;
                $pod->thumbnail_url  = $radio->thumbnail;
                $pod->content_group  = 'radio';
                $pod->region_id      = $region->id ?? 0;
                $pod->system_user_id = 1;
                $pod->type           = 'audio/mpeg';
                $pod->genre          = $radio->categories;
                $res                 = $pod->save();
                if ($res)
                    {
                        $this->info($radio->name . ' imported');
                    }
            }
        $this->info('Import completed successfully');
    }
}
