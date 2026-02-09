<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\ContentRate;
use Illuminate\Support\Carbon;

class EventRateDatatable
{
    use Helper;

    public $columns = [];

    /**
     * @param $request
     *
     * @return array{draw: int, recordsTotal: mixed, recordsFiltered: mixed, data: array}
     */
    public function data($request,$event)
    {
        $columns       = $this->columns;
        $totalData     = ContentRate::whereEventId($event)->count();
        $totalFiltered = $totalData;
        $limit         = $request->input('length');
        $start         = $request->input('start');
        $order         = $columns[$request->input('order.0.column')];
        $dir           = $request->input('order.0.dir');

        if (empty($request->input('search.value')))
        {
            $posts = ContentRate::whereEventId($event)->offset($start)->limit($limit)->orderBy($order, $dir)->get();
        }
        else
        {
            $search = $request->input('search.value');
            $posts  = ContentRate::whereEventId($event)
                                 ->where(function ($query)use($search){
								  return $query  ->where('name', 'LIKE', "%{$search}%")
								          ->orWhere('date_from', 'LIKE', "%{$search}%")
								          ->orWhere('date_to', 'LIKE', "%{$search}%")
								          ->orWhere('reserved_currency_cost', 'LIKE', "%{$search}%")
								          ->orWhere('cost', 'LIKE', "%{$search}%");
	                          })

                                 ->offset($start)
                                 ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ContentRate::whereEventId($event)
                                        ->where(function ($query)use($search){
	                                      return $query  ->where('name', 'LIKE', "%{$search}%")
	                                                     ->orWhere('date_from', 'LIKE', "%{$search}%")
	                                                     ->orWhere('date_to', 'LIKE', "%{$search}%")
	                                                     ->orWhere('reserved_currency_cost', 'LIKE', "%{$search}%")
	                                                     ->orWhere('cost', 'LIKE', "%{$search}%");
                                      })
                                        ->count();
        }

        $data = [];
        if (!empty($posts))
        {
            $pos = $start + 1;
            foreach ($posts as $post)
            {
                $btn                  = $this->button($post, $request);
                $nestedData['pos']     = $pos;
                $nestedData['name']   = $post->name ;
                $nestedData['cost']  = $post->cost;
	            $nestedData['reserved_currency_cost']  = $post->reserved_currency_cost;
	            $nestedData['date_from']  =  Carbon::parse($post->date_from)->toDayDateTimeString()??'infinity';
	            $nestedData['date_to']  =  Carbon::parse($post->date_to)->toDayDateTimeString()??'infinity';
	            //$nestedData['created_by']  = optional($post->user)->email;
                $nestedData['status'] = ($post->status == 1) ? 'Active' : 'inactive';
	            $nestedData['is_special_offer']  = ($post->is_special_offer==1)?'Yes':'No';
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
        if ($request->user()->can('edit_event'))
        {
            $button .= '<a class="text text-dark" href="' . route('event.rates.edit', [$post->event_id, $post->id]) . '" data-toggle="tooltip" title="Edit User">
                <i class="fas fa-edit"></i> Edit
                </a>';
        }
        if ($request->user()->can('destroy_event'))
        {
            $button .= '<form id="delete-form-' . $post->event_id . '-' . $post->id . '" action="' . route('event.rates.destroy',[$post->event_id, $post->id]) . '" method="POST" class=" create-form my-0 py-0">
                <input type="hidden" name="_token" value="' . csrf_token() . '" />
                <input type="hidden" name="_method" value="DELETE" class="my-0 py-0" />
                <button type="submit" class="btn btn-link text-dark" data-toggle="tooltip" title="Delete User"><i class="fas fa-trash"></i> Delete</button>
                </form>';
        }

        return '<div class="d-flex align-items-center">' . $button . "</div>";
    }
}
