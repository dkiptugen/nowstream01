<?php

namespace App\Http\Datatables;

use App\Models\ActivityLog;
use App\Models\Event;
use App\Traits\Helper;
use App\Models\Activity;
class LogDatatable
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

        $totalData      =   ActivityLog::count();
        $totalFiltered  =   $totalData;
        $limit          =   $request->input('length');
        $start          =   $request->input('start');
        $order          =   $columns[$request->input('order.0.column')];
        $dir            =   $request->input('order.0.dir');
        if(empty($request->input('search.value')))
            {
                $posts = ActivityLog::offset($start)
                                 ->limit($limit)
                                 ->orderBy($order,$dir)
                                 ->get();
            }
        else
            {
                $search =   $request->input('search.value');

                $posts  =   ActivityLog::whereHas("user",function ($subquery) use($search){
                    $subquery->where('name','LIKE',"%{$search}%");
                })
                                    ->orWhere('description','LIKE',"%{$search}%")
                                    ->orWhere('subject_type','LIKE',"%{$search}%")
                                    ->orWhere('subject_id','LIKE',"%{$search}%")
                                    ->orWhere('properties','LIKE',"%{$search}%")
                                    ->offset($start)
                                    ->limit($limit)
                                    ->orderBy($order,$dir)
                                    ->get();

                $totalFiltered =    ActivityLog::whereHas("user",function ($subquery) use($search){
                    $subquery->where('name','LIKE',"%{$search}%");
                })
                                            ->orWhere('description','LIKE',"%{$search}%")
                                            ->orWhere('subject_type','LIKE',"%{$search}%")
                                            ->orWhere('subject_id','LIKE',"%{$search}%")
                                            ->orWhere('properties','LIKE',"%{$search}%")
                                            ->count();
            }
        $data = array();
        if(!empty($posts))
            {
                $pos    =   $start+1;
                foreach ($posts as $post)
                    {

                        $nestedData['pos']          =   $pos;
                        $nestedData['action']       =   $post->description;
                        $nestedData['executer']     =   $post->user->name??'System';
                        $nestedData['model']        =   $post->subject_type;
                        $nestedData['affectedid']   =   $post->subject_id;
                        $nestedData['change']       =   $post->properties;
                        $nestedData['time']         =   $post->created_at->format('h:ia d-m-Y');

                        $data[]                     =   $nestedData;
                        $pos++;
                    }
            }

        $json_data = array("draw" => (int)$request->input('draw'), "recordsTotal" => $totalData, "recordsFiltered" => $totalFiltered, "data" => $data);

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
        if ($request->user()->can('edit_event'))
        {
            $button .= '<a class="text text-dark" href="' . route('user.edit', $post->id) . '" data-toggle="tooltip" title="Edit User">
                <i class="fas fa-edit"></i> Edit
                </a>';
        }
        if ($request->user()->can('destroy_event'))
        {
            $button .= '<form id="delete-form-' . $post->id . '" action="' . route('user.destroy', $post->id) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete User"><i class="fas fa-trash"></i> Delete</button>
                </form>';
        }

        return '<div class="d-flex align-items-center">' . $button . "</div>";
    }
}
