<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Content;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PodcastDatatable
    {
        use Helper;

        public $columns = [];

    /**
     * Datatable JSON
     */
        public function data($request): array
            {
                $limit = (int)$request->input('length', 10);
                $start = (int)$request->input('start', 0);
                $draw  = (int)$request->input('draw');

                $orderIndex = (int)$request->input('order.0.column', 0);
                $dir        = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

                $orderColumn = $this->columns[$orderIndex] ?? 'id';

                $baseQuery = Content::query()
                                    ->with(['categories:uuid,name', 'tags:id,name'])// Eager load
                                    ->withCount('children')
                                    ->where('content_group', 'podcast');

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
                            'pos'          => $pos++,
                            'title'       => e($post->title),
                            'description' => str($post->description)->limit(50),
                            'thumbnail'   => '<img src="'.$post->thumbnail_url.'" class="img-fluid" width="50" />',
                            'category'    => $post->categories?->pluck('name')->implode(', '),
                            'keywords'    => $post->tags?->pluck('name')->implode(', '),
                            'source'      => e($post->source),
                            'episodes'    => '<a href="' . route('backend.podcast.episode.index',['podcast'=>$post->uuid]) . '" class="text-dark text-underline text-bold">'
                                . $post->children_count .
                                '</a>',
                            'publishdate' => $post->publishdate,
                            'status'      => $post->status ? 'active' : 'inactive',
                            'action'      => $this->button($post, $request),
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
                if ($request->user()->can('edit_podcast'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('backend.podcast.edit', ['podcast' => $post->uuid]) . '" data-toggle="tooltip" title="Edit podcast">
                <i class="fas fa-edit"></i> Edit
                </a>';
                    }
                if ($request->user()->can('destroy_podcast'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('backend.podcast.destroy', ['podcast' => $post->uuid]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Podcast"><i class="fas fa-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
