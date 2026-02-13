<?php

    namespace App\Http\Datatables;

    use App\Models\Event;
    use App\Traits\Helper;
    use App\Models\Content;

    class RadioDatatable
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
                    $query   = Content::query()
                                      ->with('categories')
                                      ->where('content_group', 'radio');
                    $columns = $this->columns;

                    $limit         = $request->input('length');
                    $start         = $request->input('start');
                    $order         = $columns[$request->input('order.0.column')];
                    $dir           = $request->input('order.0.dir');
                    $totalData     = $query->count();
                    $totalFiltered = $totalData;
                    if (!empty($request->input('search.value')))
                        {
                            $search = $request->input('search.value');
                            $query->where('title', 'LIKE', "%{$search}%");


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
                                    $nestedData["thumbnail"]   = '<img src="'.$post->thumbnail_url.'" class=""img-fluid" style="height:50px; width:50px" />';
                                    $nestedData['language']    = $post->language;
                                    $nestedData['region']      = $post->country;
                                    $nestedData['category']    = $post->categories?->pluck('name')->implode(', ');
                                    $nestedData['action']      = $btn;
                                    $data[]                    = $nestedData;
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
                    if ($request->user()->can('edit_radio'))
                        {
                            $button .= '<a class="text text-dark" href="' . route('backend.radio.edit', ['radio' => $post->uuid]) . '" data-toggle="tooltip" title="Edit radio">
                                        <i class="fas fa-edit"></i> Edit
                                        </a>';
                        }
                    if ($request->user()->can('view_radio'))
                        {
                            $button .= '<a class="text text-dark" href="' . route('backend.radio.show', ['radio' => $post->uuid]) . '" data-toggle="tooltip" title="view radio">
                                        <i class="fas fa-play"></i> Listen
                                        </a>';
                        }
                    if ($request->user()->can('destroy_radio'))
                        {
                            $button .= '<form id="delete-form-' . $post->id . '" action="' . route('backend.radio.destroy', ['radio' => $post->uuid]) . '" method="POST" class=" create-form my-0 py-0">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                    <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                                    <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Radio"><i class="fas fa-trash"></i> Delete</button>
                                    </form>';
                        }

                    return '<div class="d-flex align-items-center">' . $button . "</div>";
                }
        }
