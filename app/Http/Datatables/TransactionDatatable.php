<?php

namespace App\Http\Datatables;

use App\Models\Event;
use App\Traits\Helper;
use App\Models\Transaction;

class TransactionDatatable
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
                $query   = Transaction::query();
                $query->with('event');
                $query->where('channel_id', $request->user()->channel_id);

                $limit         = $request->input('length');
                $start         = $request->input('start');
                $order         = $columns[$request->input('order.0.column')];
                $dir           = $request->input('order.0.dir');
                $totalData     = $query->count();
                $totalFiltered = $totalData;

                if (!empty($request->input('search.value')))
                    {
                        $search = $request->input('search.value');
                        $query->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('stream_token', 'LIKE', "%{$search}%");

                        $totalFiltered = (clone $query)->count();
                    }
                $posts = $query->offset($start)->limit($limit)->orderBy($order, $dir)->get();
                $data  = [];
                if (!empty($posts))
                    {
                        $pos = $start + 1;
                        foreach ($posts as $post)
                            {
                                $btn                          = $this->button($post, $request);
                                $nestedData['id']             = $pos;
                                $nestedData['receipt']        = $post->receipt;
                                $nestedData['payment_method'] = $post->payment_method;
                                $nestedData['status']         = ($post->status == 1) ? 'Active' : 'inactive';
                                $nestedData['event']          = $post->event->event_name;
                                $nestedData['cost']           = $post->cost;
                                $nestedData['amount_paid']    = $post->amount_paid;
                                $nestedData['balance']        = $post->balance;
                                $nestedData['date_paid']      = $post->date_paid;
                                $nestedData['name']           = $post->user->name;
                                $nestedData['email']          = $post->user->email;
                                $nestedData['action']         = $btn;

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
