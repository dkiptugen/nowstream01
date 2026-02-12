<?php

namespace App\Http\Datatables;

use App\Models\Content;
use App\Models\Event;
use App\Traits\Helper;

class VideoDatatable
    {
        use Helper;

        public $columns = [];

    /**
     * @param $request
     *
     * @return array{draw: int, recordsTotal: mixed, recordsFiltered: mixed, data: array}
     */
        public function data($request)
            {
                $columns = $this->columns;
                $query   = Content::query();

                $limit = $request->input('length');
                $start = $request->input('start');
                $order = $columns[$request->input('order.0.column')];
                $dir   = $request->input('order.0.dir');
                $query->where('content_group', 'video');
                $totalData     = $query->count();
                $totalFiltered = $totalData;

                if (!empty($request->input('search.value')))

                    {
                        $search = $request->input('search.value');
                        $query
                            ->where('title', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");

                        $totalFiltered = (clone $query)
                            ->count();
                    }
                $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();

                $data = [];
                if (!empty($posts))
                    {
                        $post = $start + 1;
                        foreach ($posts as $post)
                            {
                                $btn                       = $this->button($post, $request);
                                $nestedData['id']          = $post;
                                $nestedData['title']       = $post->title;
                                $nestedData['description'] = $post->description;
                                $nestedData['thumbnail']   = $this->thumbnail_tag($post->thumbnail_url, 'img-fluid', 'height:50px');
                                $nestedData['video']       = $post->content_path;
                                $nestedData['created_at']  = $post->created_at->toDayDateTimeString();
                                $nestedData['action']      = $btn;

                                $data[] = $nestedData;

                            }
                    }

                $json_data = [
                    'draw'            => (int)$request->input('draw'),
                    'recordsTotal'    => $totalData,
                    'recordsFiltered' => $totalFiltered,
                    'data'            => $data
                ];

                return $json_data;
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
                if ($request->user()->can('edit_channel_video'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('video.edit', ['video' => $post->id]) . '" data-toggle="tooltip" title="Edit User">
                <i class="fas fa-edit"></i> Edit
                </a>';
                    }
                if ($request->user()->can('destroy_channel_video'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('video.destroy', ['video' => $post->id]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete User"><i class="fas fa-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
