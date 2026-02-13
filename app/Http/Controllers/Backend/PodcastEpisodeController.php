<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\PodcastDatatable;
use App\Http\Datatables\PodcastEpisodeDatatable;
use App\Http\Services\UploadService;
use App\Models\Category;
use App\Models\Content;
use App\Models\Region;
use App\Models\Tag;
use App\Traits\Meta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PodcastEpisodeController extends Controller
{
        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }

    /**
     * Display a listing of the resource.
     */
        public function index(Content $podcast)
            {
                $this->data['podcast'] = $podcast;
                return view('Backend.modules.episode.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     */
        public function create(Content $podcast)
            {
                $this->data['podcast'] = $podcast;
                return view('Backend.modules.episode.add', $this->data);
            }

        public function store(Content $podcast,Request $request)
            {

            }


    /**
     * Display the specified resource.
     */
        public function show(Content $podcast, Content $episode)
            {
                $this->data['episode'] = $episode;
                return view('Backend.modules.tv.show', $this->data);
            }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Content $podcast, Content $episode)
            {
                $this->data['category'] = Category::get();
                $this->data['region'] = Region::get();
                $this->data['podcast']  = $podcast;
                $this->data['episode']  = $episode->load('tags','categories');
                return view('Backend.modules.episode.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Content $podcast, Content $episode)
            {

            }


    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Content $podcast, Content $episode)
            {
                $result = $episode->delete();
                if ($result)
                    {
                        return self::success('Videos', 'Video deleted successfully.',
                            route('backend.podcast.episode.index',['podcast'=>$podcast->uuid])
                        );
                    }
                else
                    {

                        return self::failed('Channel videos', 'Video not deleted.',
                            route('backend.podcast.episode.index',['podcast'=>$podcast->uuid])
                        );
                    }
            }


    /**
     * Custom method added for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function datatable(Request $request,Content $podcast, PodcastEpisodeDatatable $datatable)
            {
                $datatable->columns = [
                    0 => 'id',
                    1 => 'title',
                    2 => 'description',
                    6 => 'created_at'
                ];
                return response()->json($datatable->data($request, $podcast->uuid));
            }
}
