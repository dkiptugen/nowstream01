<?php

namespace App\Console\Commands;

use App\Jobs\ImportPodcastEpisodes;
use App\Models\Category;
use App\Models\Content;
use App\Models\Language;
use App\Models\Region;
use App\Traits\Meta;
use App\Libs\PodcastIndex as PI;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

ini_set('memory_limit', '-1');

class PodcastIndex extends Command
    {
        use Meta;

        protected $signature   = 'pi:import';
        protected $description = 'Import trending podcasts from Podcast Index';

        public function handle()
            {
                $pi = new PI();

                // Cache these once (avoid querying inside loop)
                $languages = Language::pluck('id', 'code');
                $region    = Region::where('name', 'undefined')->first();

                $categories = Category::get();

                $this->output->progressStart(count($categories));

                foreach ($categories as $cat)
                    {

                        try
                            {

                                $trending = $pi->trending_podcast($cat->name)->feeds ?? [];

                                foreach ($trending as $podcastData)
                                    {

                                        $title = $this->remove_emoji($podcastData->title);

                                        // 🔥 Ensure slug always exists (Chinese-safe)
                                        $slug = Str::slug(Str::ascii($title));

                                        if (empty($slug))
                                            {
                                                $slug = 'podcast-' . $podcastData->id;
                                            }

                                        $pod = Content::updateOrCreate(
                                            [
                                                'old_id'        => $podcastData->id,
                                                'source'        => 'Podcast Index',
                                                'content_group' => 'podcast'
                                            ],
                                            [
                                                'title'          => $title,
                                                'slug'           => $slug,
                                                'description'    => $this->remove_emoji($podcastData->description),
                                                'stream_url'     => $podcastData->url,
                                                'author'         => $podcastData->author,
                                                'publishdate'    => Carbon::createFromTimestamp(
                                                    $podcastData->newestItemPublishTime
                                                ),
                                                'status'         => 1,
                                                'type'           => 'rss',
                                                'language_id'    => $languages[$podcastData->language] ?? null,
                                                'thumbnail_url'  => $podcastData->image,
                                                'region_id'      => $region->id ?? null,
                                                'system_user_id' => 1,
                                            ]
                                        );

                                        // Attach category safely
                                        $pod->categories()->syncWithoutDetaching([$cat->uuid]);

                                        // Dispatch episode import job
                                        ImportPodcastEpisodes::dispatch(
                                            $podcastData->id,
                                            $pod->uuid
                                        )->onQueue('podcasts');

                                    }

                            }
                        catch (\Throwable $e)
                            {
                                logger()->error("Podcast import error: " . $e->getMessage());
                            }

                        $this->output->progressAdvance();
                    }

                $this->output->progressFinish();

                return Command::SUCCESS;
            }
    }
