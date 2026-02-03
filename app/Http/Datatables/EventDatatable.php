<?php

    namespace App\Http\Datatables;

    use App\Enums\ActivityStatus;
    use App\Models\Channel;
    use App\Models\Event;
    use App\Traits\Helper;
    use Illuminate\Support\Facades\Log;

    class EventDatatable
        {
            use Helper;

            public $columns = [];

            public function data($request)
                {
                    $columns       = $this->columns;
                    $query         = Event::query();
                    //$query->where('channel_id',$request->user()->channel_id);
                    $limit         = $request->input('length');
                    $start         = $request->input('start');
                    $order         = $columns[$request->input('order.0.column')];
                    $dir           = $request->input('order.0.dir');

                    $totalData     = $query->count();
                    $totalFiltered = $totalData;
                    $query->withCount('videos')
                          ->withCount('rates');;

                    if (!empty($request->input('search.value')))
                        {

                            $search = $request->input('search.value');
                            $query->where('event_name', 'LIKE', "%{$search}%")
                                           ->orWhere('description', 'LIKE', "%{$search}%")
                                           ->orWhere('status', 'LIKE', "%{$search}%")
                                          ;

                            $totalFiltered = (clone $query)->count();
                        }
                    $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();

                    $data = [];
                    if (!empty($posts))
                        {

                            $pos = $start + 1;
                            foreach ($posts as $post)
                                {
									Log::info($post->streams);

                                    $btn                        = $this->button($post, $request);
                                    $nestedData['pos']          = $pos;
                                    $nestedData['event_name']   = $post->event_name;
                                    $nestedData['thumbnail']    = $this->thumbnail_tag($post->event_image, 'img-fluid', 'height:50px; width:50px;');
                                    $nestedData['stream_key']   = $this->anchor_link (optional($post->streams)
	                                    ->stream_key, route('backend.event.stream.index', $post->id));
									$nestedData['publish_date'] = $post->publish_date->toDayDateTimeString();
									$nestedData['videos']       = $this->anchor_link($post->videos_count, route('backend.event.video.index', $post->id));
                                    $nestedData['created_at']   = $post->created_at->toDayDateTimeString();
                                    $nestedData['rates']        = $this->anchor_link($post->rates_count, route('backend.event.rates.index', $post->id));
                                    $nestedData['status']       = ActivityStatus::from($post->status)->name;
                                    $nestedData['action']       = $btn;
                                    $data[]                     = $nestedData;
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

            private function button($post, $request)
                {

                    $button = null;
                    if ($request->user()->can('edit_event'))
                        {
                            $button .= '<a class="text text-dark" href="' . route('backend.event.edit', ['event'=>$post->id]) . '" data-toggle="tooltip" title="Edit Event">
                                                <i class="fas fa-edit "></i>
                                                </a>';
                        }
                    if ($request->user()->can('destroy_event'))
                        {
                            $button .= '<form id="delete-form-' . $post->id . '" action="' . route('backend.event.destroy', ['event'=>$post->id]) . '" method="POST" class=" create-form my-0 py-0">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                        <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                                        <button type="submit" class="btn btn-link text-dark text-decoration-none font-weight-normal"   data-toggle="tooltip" title="Delete Event"><i class="fas fa-trash "></i> </button>
                                        </form>';
                        }
	                if ($request->user()->can('view_event_subscription'))
		                {
			                $button .= '<a class="text text-dark mr-2" href="' . route('backend.event.subscription.index', $post->id) . '" data-toggle="tooltip" title="Show Subscriptions">
                                                <i class="fas fa-paperclip"></i>
                                                </a>';
		                }
	                if ($request->user()->can('view_event_transaction'))
		                {
			                $button .= '<a class="text text-dark mr-2" href="' . route('backend.event.transaction.index',$post->id) . '" data-toggle="tooltip" title="Show Transactions">
                                                <i class="fas fa-credit-card"></i>
                                                </a>';
		                }
                   /* if ($request->user()->can('view_stream'))
                        {
                            $button .= '<a class="text text-dark mr-2" href="' . route('event.stream.show', [$post->id, optional($post->streams)->id]) . '" data-toggle="tooltip" title="Show Stream">
                                                <i class="fas fa-eye"></i>
                                                </a>';
                        }*/
                    if ($request->user()->can('create_video'))
                        {
                            $button .= '<a class="text text-dark" href="' . route('backend.event.video.create', $post->id) . '" data-toggle="tooltip" title="Upload Video">
                                                <i class="fas fa-upload"></i>
                                                </a>';
                        }

                    return '<div class="d-flex align-items-center justify-content-between">' . $button . "</div>";
                }
        }
