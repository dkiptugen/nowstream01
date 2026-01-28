<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Stream;

class ChannelStreamDatatable
    {
        use Helper;

        public $columns = [];

    /**
     * @param $request
     *
     * @return array{draw: int, recordsTotal: mixed, recordsFiltered: mixed, data: array}
     */
        public function data($request, $channelId)
            {
                $columns       = $this->columns;
                $totalData     = Stream::where('channel_id', $channelId)->count();
                $totalFiltered = $totalData;
                $limit         = $request->input('length');
                $start         = $request->input('start');
                $order         = $columns[$request->input('order.0.column')];
                $dir           = $request->input('order.0.dir');

                if (empty($request->input('search.value')))
                    {
                        $posts = Stream::where('channel_id', $channelId)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                    }
                else
                    {
                        $search = $request->input('search.value');
                        $posts  = Stream::where('channel_id', $channelId)->where('name', 'LIKE', "%{$search}%")
                                        ->orWhere('title', 'LIKE', "%{$search}%")
                                        ->orWhere('description', 'LIKE', "%{$search}%")
                                        ->offset($start)->limit($limit)->orderBy($order, $dir)->get();

                        $totalFiltered = Stream::where('channel_id', $channelId)->where('name', 'LIKE', "%{$search}%")
                                               ->orWhere('title', 'LIKE', "%{$search}%")
                                               ->orWhere('description', 'LIKE', "%{$search}%")
                                               ->count();
                    }

                $data = [];
                if (!empty($posts))
                    {
                        $pos = $start + 1;
                        foreach ($posts as $post)
                            {
                                $btn                       = $this->button($post, $request, $channelId);
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
        private function button($post, $request, $channel)
            {
                $button = null;
                if ($request->user()->can('edit_channel_stream'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('channel.stream.edit', [$channel, $post->id]) . '" data-toggle="tooltip" title="Edit User">
                <i class="bx bx-pencil"></i> Edit
                </a>';
                    }
                if ($request->user()->can('destroy_channel_stream'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('channel.stream.destroy', [$channel, $post->id]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Stream"><i class="bx bx-trash"></i> Delete</button>
                </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
