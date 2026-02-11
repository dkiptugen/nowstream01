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
                $columns       = $this->columns;
                $query         = Content::query();
                $query
                    //->where('channel_id', $request->user()->channel_id)
                      ->where('content_group', 'stream');

                $limit         = $request->input('length');
                $start         = $request->input('start');
                $order         = $columns[$request->input('order.0.column')];
                $dir           = $request->input('order.0.dir');
                $totalData     = $query->count();
                $totalFiltered = $totalData;

                if (!empty($request->input('search.value')))

                    {
                        $search = $request->input('search.value');
                        $posts  = $query->where('name', 'LIKE', "%{$search}%")
                                        ->orWhere('title', 'LIKE', "%{$search}%")
                                        ->orWhere('description', 'LIKE', "%{$search}%");

                        $totalFiltered = (clone $query)->count();
                    }
                $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                $data = [];
                if (!empty($posts))
                    {
                        $pos = $start + 1;
                        foreach ($posts as $post)
                            {
                                $btn                       = $this->button($post, $request);
                                $nestedData['pos']         = $pos;
                                $nestedData['title']       = $post->title;
                                $nestedData['description'] = $post->description;
                                $nestedData['stream_key']  = $post->stream_key;
                                $nestedData['stream_url']  = $post->stream_url;
                                $nestedData['stream_link'] = $post->stream_video_link;
                                $nestedData["thumbnail"]   = $this->thumbnail_tag($post->thumbnail_url, 'img-fluid', 'height:50px; width:50px');
                                $nestedData['start_time']  = $post->start_time;
                                $nestedData['end_time']    = $post->end_time;
                                $nestedData['is_ended']    = (bool)$post->ended;
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
                if ($request->user()->can('edit_channel_stream'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('stream.edit', ['stream'=> $post->id]) . '" data-toggle="tooltip" title="Edit User">
                <i class="bx bx-pencil"></i> Edit
                </a>';
                    }
                if ($request->user()->can('destroy_channel_stream'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('stream.destroy', ['stream'=> $post->id]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Content"><i class="bx bx-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
