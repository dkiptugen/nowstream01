<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class IPTvStreamImport extends Command
    {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected        $signature = 'import:tvstreams';
        protected string $baseUrl   = 'https://iptv-org.github.io/api';
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
                $streams = Http::timeout(120)
                               ->get($this->baseUrl . '/streams.json')
                               ->json();
                foreach ($streams as $stream)
                    {
                        $content = Content::where('old_id', $stream['channel'])
                                          ->orWhere('title', $stream['title'])
                                          ->orWhere('title', $stream['feed'])
                                          ->first();
                        if (is_null($content))
                            {
                                $content                 = new Content();
                                $content->title          = $stream['title'];
                                $content->old_id         = $stream['channel'];
                                $content->source         = 'iptv_org';
                                $content->region_id      = 0;
                                $content->description    = '';
                                $content->country        = null;
                                $content->type           =  'application/x-mpegURL';
                                $content->author         = 'streams';
                                $content->content_group  = 'tv';
                                $content->status         = 1;
                                $content->system_user_id = 1;
                                $res                     = $content->save();
                                if ($res)
                                    {
                                        $this->info($stream['title']);
                                    }
                            }
                        else
                            {
                                $content->stream_url = $stream['url'];
                                $content->status     = 1;
                                $res                 = $content->save();
                                if ($res)
                                    {
                                        $this->info($stream['title']);
                                    }
                            }
                    }
                $this->info('Import completed successfully');
            }
    }
