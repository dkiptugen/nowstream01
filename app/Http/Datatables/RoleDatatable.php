<?php

namespace App\Http\Datatables;


use App\Models\Role;
use App\Traits\Helper;

class RoleDatatable
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
        $totalData     = Role::count();
        $totalFiltered = $totalData;
        $limit         = $request->input('length');
        $start         = $request->input('start');
        $order         = $columns[$request->input('order.0.column')];
        $dir           = $request->input('order.0.dir');

        if (empty($request->input('search.value')))
        {
            $posts = Role::offset($start)->limit($limit)->orderBy($order, $dir)->get();
        }
        else
        {
            $search = $request->input('search.value');
            $posts  = Role::where('name', 'LIKE', "%{$search}%")
                 ->orWhere('name', 'LIKE', "%{$search}%")
                ->offset($start)->limit($limit)->orderBy($order, $dir)->get();

            $totalFiltered = Role::where('name', 'LIKE', "%{$search}%")
                 ->orWhere('name', 'LIKE', "%{$search}%")
                ->count();
        }

        $data = [];
        if (!empty($posts))
        {
            $pos = $start + 1;
            foreach ($posts as $post)
            {
                $btn                  = $this->button($post, $request);
                $nestedData['id']     = $pos;
                $nestedData['name']   = trim($post->name);
                $nestedData['access']  =$this->tags($post->getAllPermissions()->pluck('display_name')->toArray());
                $nestedData['action'] = $btn;

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
        if($request->user()->can('add_permission_role'))
            {
                $button .= '<a class="text text-dark" href="'.route('role.assign_view',$post->id).'" data-toggle="tooltip" title="Assign Permissions to Role">
                                                <i class="bx bx-plus-circle"></i> Assign Permissions
                                                </a>';
            }
        if($request->user()->can('edit_role'))
            {
                $button .= '<a class="text text-dark" href="'.route('role.edit',$post->id).'" data-toggle="tooltip" title="Edit Role">
                                                <i class="bx bx-pencil"></i> Edit
                                                </a>';
            }

        if($request->user()->can('destroy_role'))
            {
                $button .='<form id="delete-form-' . $post->id . '" action="' . route('role.destroy',$post->id) . '" method="POST" class=" create-form m-0 p-0">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                        <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                                        <button type="submit" class="btn btn-link text-dark m-0" data-toggle="tooltip" title="Delete Role"><i class="bx bx-trash m-0"></i> Delete</button>
                                        </form>';
            }

        return '<div class="d-flex align-items-center">' . $button . "</div>";
    }
}
