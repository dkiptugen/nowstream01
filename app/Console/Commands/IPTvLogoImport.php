<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class IPTvLogoImport extends Command
    {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected        $signature = 'import:tvlogos';
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
                $channels = Http::timeout(120)
                                ->get($this->baseUrl . '/logos.json')
                                ->json();
                foreach ($channels as $channel)
                    {
                        $content = Content::where('old_id', $channel['channel'])
                                          ->orWhere('title', $channel['feed'])
                                          ->first();
                        if (!is_null($channel))
                            {
                                try
                                    {
                                        $content->thumbnail_url = $channel['url'];
                                        $content->genre         = is_null($content->genre) ? json_encode($channel['genres'] ?? []) : $content->genre;
                                        $res                    = $content->save();
                                        if ($res)
                                            {

                                                $this->info($content->title);
                                            }
                                    }
                                catch (\Exception $e)
                                    {
                                        $this->error($e->getMessage());
                                    }

                            }
                    }
                $this->info('Import completed successfully');
            }
    }
