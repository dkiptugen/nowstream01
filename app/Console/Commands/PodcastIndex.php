<?php

namespace App\Console\Commands;

use App\Jobs\ImportPodcastEpisodes;
use App\Models\Category;
use App\Models\Content;
use App\Models\Language;

use App\Models\Region;
use App\Traits\Meta;
use App\Libs\PodcastIndex as PI;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

ini_set('memory_limit', '-1');

class PodcastIndex extends Command
    {
        use Meta;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
        protected $signature = 'pi:import';

    /**
     * The console command description.
     *
     * @var string
     */
        protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
        public function handle()
            {
                $pi = new PI();

                $categories = $pi->podcastCategories()->feeds;

                $this->output->progressStart(count($categories));

                foreach ($categories as $cat)
                    {

                        $db_cat = Category::firstOrCreate(
                            ['name' => $cat->name],
                            [
                                'system_user_id' => 1,
                                'position'       => 1,
                                'type'           => ['podcast'],
                                'status'         => 1,
                                'description'    => ''
                            ]
                        );

                        $trending = $pi->trending_podcast($db_cat->name)->feeds ?? [];

                        foreach ($trending as $podcastData)
                            {

                                $language = Language::where('code', $podcastData->language)->first();
                                $region   = Region::where('name', 'undefined')->first();

                                $pod = Content::updateOrCreate(
                                    [
                                        'old_id'        => $podcastData->id,
                                        'source'        => 'Podcast Index',
                                        'content_group' => 'podcast'
                                    ],
                                    [
                                        'title'          => $this->remove_emoji($podcastData->title),
                                        'description'    => $this->remove_emoji($podcastData->description),
                                        'stream_url'     => $podcastData->url,
                                        'author'         => $podcastData->author,
                                        'publishdate'    => Carbon::createFromTimestamp($podcastData->newestItemPublishTime),
                                        'status'         => 1,
                                        'type'           => 'rss',
                                        'language_id'    => $language->id ?? null,
                                        'thumbnail_url'  => $podcastData->image,
                                        'region_id'      => $region->id ?? null,
                                        'system_user_id' => 1,
                                    ]
                                );

                                $pod->categories()->syncWithoutDetaching([$db_cat->uuid]);

                                ImportPodcastEpisodes::dispatch(
                                    $podcastData->id,
                                    $pod->uuid
                                );

                            }

                        $this->output->progressAdvance();
                    }

                $this->output->progressFinish();

                return Command::SUCCESS;
            }


    }
