<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Content;

class TvDatatable
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
                $query->where('content_group', 'tv');

                $limit         = $request->input('length');
                $start         = $request->input('start');
                $order         = $columns[$request->input('order.0.column')];
                $dir           = $request->input('order.0.dir');
                $totalData     = $query->count();
                $totalFiltered = $totalData;

                if (!empty($request->input('search.value')))

                    {
                        $search = $request->input('search.value');
                        $query
                            ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('title', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");

                        $totalFiltered = (clone $query)->count();
                    }
                $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                $data  = [];
                if (!empty($posts))
                    {
                        $pos = $start + 1;
                        foreach ($posts as $post)
                            {
                                if(is_null($post->stream_url))
                                    {
                                        $post->status = 0;
                                        $post->save();
                                    }
                                $btn                      = $this->button($post, $request);
                                $nestedData['pos']        = $pos;
                                $nestedData['title']      = $post->title;
                                $nestedData["thumbnail"]  = '<img src="' . $post->thumbnail_url . '" class="img-fluid" width="50" height="50" />';
                                $nestedData['region']     = $post->country;
                                $nestedData['language']   = $post->language;
                                $nestedData['category']   = $post->categories?->pluck('name')->implode(', ');
                                $nestedData['status']     = $post->status ? 'Active' : 'Inactive';
                                $nestedData['action']     = $btn;
                                $data[]                   = $nestedData;
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
                if ($request->user()->can('edit_tv'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('backend.tv.edit', ['tv' => $post->uuid]) . '" data-toggle="tooltip" title="Edit Tv">
                                    <i class="bx bx-pencil"></i> Edit
                                    </a>';
                    }
                if ($request->user()->can('view_tv'))
                    {
                        $button .= '<a class="text text-dark" href="' . route('backend.tv.show', ['tv' => $post->uuid]) . '" data-toggle="tooltip" title="show Tv">
                                    <i class="bx bx-eye"></i> View
                                    </a>';
                    }
                if ($request->user()->can('destroy_tv'))
                    {
                        $button .= '<form id="delete-form-' . $post->id . '" action="' . route('backend.tv.destroy', ['tv' => $post->uuid]) . '" method="POST" class=" create-form my-0 py-0">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                    <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                                    <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete Content"><i class="bx bx-trash"></i> Delete</button>
                                    </form>';
                    }

                return '<div class="d-flex align-items-center">' . $button . "</div>";
            }
    }
