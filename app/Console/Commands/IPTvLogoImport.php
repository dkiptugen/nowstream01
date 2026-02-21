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
                //$this->info(collect($channels)->toJson());
                foreach ($channels as $channel)
                    {
                        //dd($channel);
                        try
                            {

                                $content = Content::where('old_id', $channel['channel'] ?? null)
                                                  ->orWhere('title', $channel['feed'] ?? null)
                                                  ->first();

                                if (!$content)
                                    {
                                        $this->warn("Content not found for: " . ($channel['channel'] ?? 'unknown'));
                                        continue; // ✅ skip instead of breaking loop
                                    }

                                $content->thumbnail_url = $channel['url'] ?? asset('assets/img/no-logo.jpg');

                                if (empty($content->genre))
                                    {
                                        $content->genre = json_encode($channel['genres'] ?? []);
                                    }

                                $content->save();

                                $this->info("Updated: " . $content->title);

                            }
                        catch (\Throwable $e)
                            {
                                $this->error("Error processing channel: " . $e->getMessage());
                                continue; // ✅ continue even if error happens
                            }

                    }
                $this->info('Import completed successfully');
            }
    }
