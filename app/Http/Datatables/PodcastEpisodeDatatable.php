<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Content;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class PodcastEpisodeDatatable
    {
        use Helper;

        public $columns = [];

    /**
     * Datatable JSON
     */
        public function data($request, $podcast_id): array
            {
                $limit = (int)$request->input('length', 10);
                $start = (int)$request->input('start', 0);
                $draw  = (int)$request->input('draw');

                $orderIndex = (int)$request->input('order.0.column', 0);
                $dir        = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

                $orderColumn = $this->columns[$orderIndex] ?? 'id';

                $baseQuery = Content::query()
                                    ->with(['categories:uuid,name', 'tags:id,name'])// Eager load
                                    ->where('content_group', 'podcast_episode')
                                    ->where('parent_id', $podcast_id);

                $totalData = (clone $baseQuery)->count();

                /**
                 * SEARCH
                 */
                if ($search = $request->input('search.value'))
                    {
                        $baseQuery->where(function (Builder $query) use ($search)
                            {
                                $query
                                    ->where('title', 'LIKE', "%{$search}%")
                                    ->orWhere('description', 'LIKE', "%{$search}%");
                            });
                    }

                $totalFiltered = (clone $baseQuery)->count();

                /**
                 * FETCH DATA
                 */
                $posts = $baseQuery
                    ->orderBy($orderColumn, $dir)
                    ->offset($start)
                    ->limit($limit)
                    ->get();

                $data = [];
                $pos  = $start + 1;

                foreach ($posts as $post)
                    {
                        $data[] = [
                            'pos'            => $pos++,
                            'title'          => e($post->title),
                            'description'    => str($post->description)->limit(50),
                            'thumbnail'      => '<img src="' . $post->thumbnail_url . '" class="img-fluid" width="50" />',
                            'source'         => e($post->source),
                            'duration'       => $post->duration,
                            'episodes'       => '<a href="' . route('backend.podcast.episode.index', ['podcast' => $post->uuid]) . '" class="text-dark text-underline text-bold">'
                                . $post->children_count .
                                '</a>',
                            'content_rating' => $post->is_explicit ? 'Explicit' : 'Not Explicit',
                            'publishdate'    => $post->publishdate,
                            'status'         => $post->status ? 'active' : 'inactive',
                            'action'         => $this->button($post, $request),
                        ];
                    }

                return [
                    'draw'            => $draw,
                    'recordsTotal'    => $totalData,
                    'recordsFiltered' => $totalFiltered,
                    'data'            => $data,
                ];
            }


    /**
     * @param $post
     * @param $request
     *
     * @return string
     */
        private function button($post, $request)
            {
                $button = null;
                if ($request->user()->can('edit_podcast_episode'))
                    {
                        $button .= '<a class="btn btn-primary btn-sm" style="white-space: nowrap;" href="' . route('backend.podcast.episode.edit', ['podcast' => $post->parent_id, 'episode' => $post->uuid]) . '" data-toggle="tooltip" title="Edit podcast Episode">
                <i class="fas fa-edit"></i> Edit
                </a>';
                    }
                if ($request->user()->can('view_podcast_episode'))
                    {
                        $button .= '<a class="btn btn-dark btn-sm" style="white-space: nowrap;" href="' . route('backend.podcast.episode.show', ['podcast' => $post->parent_id, 'episode' => $post->uuid]) . '" data-toggle="tooltip" title="Listen podcast Episode">
                <i class="fas fa-play-circle"></i> Listen
                </a>';
                    }
                if ($request->user()->can('destroy_podcast_episode'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('backend.podcast.episode.destroy', ['podcast' => $post->parent_id, 'episode' => $post->uuid]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Delete Podcast Episode" style="white-space: nowrap;"><i class="fas fa-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="btn-group btn-group-sm">' . $button . "</div>";
            }
    }
