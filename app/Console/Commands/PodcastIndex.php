<?php

namespace App\Console\Commands;

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

                try
                    {
                        $podcast  = new PI();
                        $category = $podcast->podcastCategories()->feeds;
                        foreach ($category as $cat)
                            {

                                $db_cat = Category::firstOrCreate(['name' => $cat->name], ['system_user_id' => 1, 'position' => 1, 'type' => ['podcast'], 'status' => 1, 'description' => '']);
                                //dd($db_cat);
                                foreach ($podcast->trending_podcast($db_cat->name)->feeds as $podcasts)
                                    {


                                        $region   = Region::where('name', 'undefined')->first();

                                        $language = Language::where('code', $podcasts->language)->first();
                                        try
                                            {
                                                $response = Http::head($podcasts->url);

                                                $type = $response->header('Content-Type');
                                            }
                                        catch (\Exception $e)
                                            {
                                                $type = 'undefined';
                                            }


                                        try
                                            {
                                                $pod                 = Content::firstOrNew(['old_id' => $podcasts->id, 'source' => 'Podcast Index', 'content_group' => 'podcast']);
                                                $pod->title          = ($this->remove_emoji($podcasts->title) == "")
                                                    ? substr($this->remove_emoji($podcasts->description), 0, 10) . '...'
                                                    : $this->remove_emoji($podcasts->title);
                                                $pod->description    = $this->remove_emoji($podcasts->description);
                                                $pod->stream_url     = $podcasts->url;
                                                $pod->author         = $podcasts->author;
                                                $pod->source         = 'Podcast Index';
                                                $pod->publishdate    = date('Y-m-d H:i:s', $podcasts->newestItemPublishTime);
                                                $pod->status         = ($type=='undefined')?0:1;;
                                                $pod->type           = $type;
                                                $pod->language_id    = $language->id ?? 0;
                                                $pod->language       = $podcasts->language;
                                                $pod->thumbnail_url  = $podcasts->image;
                                                $pod->content_group  = 'podcast';
                                                $pod->region_id      = $region->id ?? 0;
                                                $pod->system_user_id = 1;
                                                $res                 = $pod->save();
                                                if ($res)
                                                    {
                                                        //dd($pod->categories()->sync([$db_cat->id]));
                                                        $pod->categories()->attach($db_cat->uuid);
                                                        $this->get_episode($podcasts->id, $pod->uuid);

                                                    }
                                                echo "\n" . $podcasts->title;
                                            }
                                        catch (Exception $e)
                                            {
                                                Log::error($e->getMessage());
                                            }

                                    }
                            }
                    }
                catch (Exception $e)
                    {
                        Log::error($e->getMessage());
                    }

                return Command::SUCCESS;
            }

        public function get_episode($id, $pid)
            {

                try
                    {
                        $podcast  = new PI();
                        $episodes = $podcast->episodes($id);
                        foreach ($episodes->items as $episode)
                            {
                                //dd($pid);
                                $ep = Content::updateOrCreate([
                                    'old_id' => $episode->id
                                ], [
                                    'title'          => $this->remove_emoji($episode->title),
                                    'slug'           => SlugService::createSlug(Content::class, 'slug', $episode->title),
                                    'parent_id'      => $pid,
                                    'content_group'  => 'podcast_episode',
                                    'source'         => 'Podcast Index',
                                    'type'           => $episode->enclosureType,
                                    'description'    => $this->remove_emoji($episode->description),
                                    'duration'       => $episode->duration,
                                    'content_path'   => $episode->enclosureUrl,
                                    'publishdate'    => date('Y-m-d H:i:s', $episode->datePublished),
                                    'thumbnail_url'  => is_null($episode->feedImage) ? 'https://www.podcastindex.org/images/podcast-index-logo.png' : $episode->feedImage,
                                    'status'         => 1,
                                    'is_explicit'    => $episode->explicit,
                                    'system_user_id' => 1,
                                ]);




                            }

                    }
                catch (Exception $e)
                    {
                        Log::error($e->getMessage());
                    }
            }
    }
