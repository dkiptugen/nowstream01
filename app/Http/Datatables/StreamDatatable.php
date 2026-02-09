<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Content;

class StreamDatatable
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
                $limit   = $request->input('length');
                $start   = $request->input('start');
                $order   = $columns[$request->input('order.0.column')];
                $dir     = $request->input('order.0.dir');
                $query->where('content_group', 'stream');
                $totalData     = $query->count();
                $totalFiltered = $totalData;
                if (!empty($request->input('search.value')))

                    {
                        $search = $request->input('search.value');
                        $query->where('name', 'LIKE', "%{$search}%");

                        $totalFiltered = (clone $query)->count();
                    }
                $posts = $query
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $data = [];
                if (!empty($posts))
                    {
                        $pos = $start + 1;
                        foreach ($posts as $post)
                            {
                                $btn                       = $this->button($post, $request);
                                $nestedData['id']          = $pos;
                                $nestedData['title']       = $post->title;
                                $nestedData['description'] = $post->description;
                                $nestedData['stream_key']  = $post->stream_key;
                                $nestedData['stream_url']  = $post->stream_url;
                                $nestedData['stream_link'] = $post->stream_video_link;
                                $nestedData['event']       = $post->event->event_name;
                                $nestedData['status']      = ($post->status == 1) ? 'Active' : 'inactive';
                                $nestedData['is_ended']    = (bool)$post->ended;
                                $nestedData['start_time']  = $post->start_time->format('d-m-Y');
                                $nestedData['end_time']    = $post->end_time->format('d-m-Y');
                                $nestedData['thumbnail']   = $this->thumbnail_tag($post->thumbnail_url, 'img-fluid', 'height:50px; width:50px');
                                $nestedData['action']      = $btn;

                                $data[] = $nestedData;
                                $pos++;
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
                if ($request->user()->can('edit_stream'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('stream.edit', ['stream'=>$post->uuid]) . '" data-toggle="tooltip" title="Edit Stream">
                <i class="fas fa-edit"></i> Edit
                </a>';
                    }
                if ($request->user()->can('destroy_stream'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('stream.destroy', ['stream'=>$post->uuid]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Stream"><i class="fas fa-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
