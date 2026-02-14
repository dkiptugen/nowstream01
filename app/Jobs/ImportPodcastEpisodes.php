<?php

namespace App\Jobs;

use App\Models\Content;
use App\Libs\PodcastIndex as PI;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ImportPodcastEpisodes implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $podcastId;
        public $parentUuid;

        public function __construct($podcastId, $parentUuid)
            {
                $this->podcastId  = $podcastId;
                $this->parentUuid = $parentUuid;
            }

        public function handle()
            {
                $pi       = new PI();
                $episodes = $pi->episodes($this->podcastId);

                foreach ($episodes->items ?? [] as $episode)
                    {

                        $type = pathinfo(parse_url($episode->enclosureUrl, PHP_URL_PATH), PATHINFO_EXTENSION);

                        Content::updateOrCreate(
                            ['old_id' => $episode->id],
                            [
                                'title'          => $episode->title,
                                'slug'           => SlugService::createSlug(Content::class, 'slug', $episode->title),
                                'parent_id'      => $this->parentUuid,
                                'content_group'  => 'podcast_episode',
                                'source'         => 'Podcast Index',
                                'description'    => $episode->description,
                                'duration'       => $episode->duration,
                                'stream_url'     => $episode->enclosureUrl,
                                'publishdate'    => Carbon::createFromTimestamp($episode->datePublished),
                                'thumbnail_url'  => $episode->feedImage ?? null,
                                'status'         => 1,
                                'type'           => $type,
                                'is_explicit'    => $episode->explicit,
                                'system_user_id' => 1,
                            ]
                        );
                    }
            }
    }
