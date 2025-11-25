<?php

	namespace App\Http\Datatables;

	use App\Enums\ActivityStatus;
    use App\Models\Channel;
    use App\Traits\Helper;

    class ChannelDatatable
		{
            use Helper;
            public $columns = [];
            public function data($request)
                {
                    $columns       = $this->columns;
                    $totalData     = Channel::when($request->user()->type == 'stream_partner',function ($query)use($request){
                        return $query->where('stream_partner_id',$request->user()->id);
                    })->count();
                    $totalFiltered = $totalData;
                    $limit         = $request->input('length');
                    $start         = $request->input('start');
                    $order         = $columns[$request->input('order.0.column')];
                    $dir           = $request->input('order.0.dir');

                    if (empty($request->input('search.value')))
                        {
                            $posts = Channel::withCount('events')
                                            ->when($request->user()->type == 'stream_partner',function ($query)use($request){
                                                return $query->where('stream_partner_id',$request->user()->id);
                                            })
                                            ->offset($start)
                                              ->limit($limit)
                                              ->orderBy($order, $dir)
                                              ->get();
                        }
                    else
                        {

                            $search = $request->input('search.value');
                            $posts  = Channel::withCount('events')
                                ->when($request->user()->type == 'stream_partner',function ($query)use($request){
                                    return $query->where('stream_partner_id',$request->user()->id);
                                })
                                             ->where('name', 'LIKE', "%{$search}%")
                                           ->orWhere('email', 'LIKE', "%{$search}%")
                                           ->orWhere('status', 'LIKE', "%{$search}%")
                                           ->offset($start)
                                           ->limit($limit)
                                           ->orderBy($order, $dir)
                                           ->get();

                            $totalFiltered = Channel::when($request->user()->type == 'stream_partner',function ($query)use($request){
                                return $query->where('stream_partner_id',$request->user()->id);
                            })->where('name', 'LIKE', "%{$search}%")
                                                  ->orWhere('email', 'LIKE', "%{$search}%")
                                                  ->orWhere('status', 'LIKE', "%{$search}%")
                                                  ->count();
                        }

                    $data = [];
                    if (!empty($posts))
                        {
                            $pos = $start + 1;
                            foreach ($posts as $post)
                                {

                                    $nestedData['pos']        = $pos;
                                    $nestedData['name']      = $post->name;
                                    $nestedData['thumbnail']  = $this->thumbnail_tag($post->thumbnail,'img-fluid rounded','height:30px; width:30px;');
                                    $nestedData['description'] = $post->description;
                                    $nestedData['events']   = $post->events_count;
                                    $nestedData['status']    = ActivityStatus::from($post->status)->name ;
                                    $nestedData['created_at']    = $post->created_at->toDayDateTimeString() ;
                                    $nestedData['action']    =  $this->button($post,$request);
                                    $data[]                  = $nestedData;
                                    $pos++;
                                }
                        }

                    $json_data = [
                        "draw"            => (int)$request->input('draw'),
                        "recordsTotal"    => $totalData,
                        "recordsFiltered" => $totalFiltered,
                        "data"            => $data
                    ];

                    return $json_data;
                }
            private function button($post,$request)
                {

                    $button = null;
                   if($request->user()->can('edit_channel'))
                        {
                            $button .= '<a class="text text-dark" href="'.route('channel.edit',$post->id).'" data-toggle="tooltip" title="Edit User">
                                                <i class="bx bx-pencil"></i> Edit
                                                </a>';
                        }
                    if($request->user()->can('destroy_channel'))
                        {
                            $button .='<form id="delete-form-' . $post->id . '" action="' . route('channel.destroy',$post->id) . '" method="POST" class=" create-form my-0 py-0">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                        <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                                        <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete User"><i class="bx bx-trash"></i> Delete</button>
                                        </form>';
                        }

                    return '<div class="d-flex align-items-center ">'.$button."</div>";
                }
		}
