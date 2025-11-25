<?php

    namespace App\Http\Datatables;

    use App\Models\PaymentMethod;
    use App\Traits\Helper;


    class PaymentMethodDatatable
        {
            use Helper;

            public $columns = [];

            public function data($request)
                {
                    $columns   = $this->columns;
                    $totalData = PaymentMethod::count();

                    $totalFiltered = $totalData;
                    $limit         = $request->input('length');
                    $start         = $request->input('start');
                    $order         = $columns[$request->input('order.0.column')];
                    $dir           = $request->input('order.0.dir');

                    if (empty($request->input('search.value')))
                        {
                            $posts = PaymentMethod::with(['user'])
                                                  ->offset($start)
                                                  ->limit($limit)
                                                  ->orderBy($order, $dir)
                                                  ->get();
                        }
                    else
                        {

                            $search = $request->input('search.value');
                            $posts  = PaymentMethod::with(['user'])
                                                   ->where('name', 'LIKE', "%{$search}%")
                                                   ->orWhere('identifier', 'LIKE', "%{$search}%")
                                                   ->orWhere('provider', 'LIKE', "%{$search}%")
                                                   ->orWhere('type', 'LIKE', "%{$search}%")
                                                   ->orWhereHas('user', function ($ql) use ($search) {

                                                       return $ql->where('name', 'LIKE', "%{$search}%")
                                                                 ->orWhere('email', 'LIKE', "%{$search}%");
                                                   })
                                                   ->offset($start)
                                                   ->limit($limit)
                                                   ->orderBy($order, $dir)
                                                   ->get();

                            $totalFiltered = PaymentMethod::with(['user'])
                                                          ->where('name', 'LIKE', "%{$search}%")
                                                          ->orWhere('identifier', 'LIKE', "%{$search}%")
                                                          ->orWhere('provider', 'LIKE', "%{$search}%")
                                                          ->orWhere('type', 'LIKE', "%{$search}%")
                                                          ->orWhereHas('user', function ($ql) use ($search) {

                                                              return $ql->where('name', 'LIKE', "%{$search}%")
                                                                        ->orWhere('email', 'LIKE', "%{$search}%");
                                                          })
                                                          ->count();
                        }

                    $data = [];
                    if (!empty($posts))
                        {
                            $pos = $start + 1;
                            foreach ($posts as $post)
                                {
                                    $btn = $this->button($post, $request);

                                    $nestedData['pos']        = $pos;
                                    $nestedData['provider']   = $post->provider;
                                    $nestedData['identifier'] = $post->identifier;
                                    $nestedData['status']     = $post->status;
                                    $nestedData['notify']     = ($post->notifying == 1)
                                        ? '<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="customSwitch' . $post->id . '"  checked disabled>
                                        <label class="custom-control-label" for="customSwitch' . $post->id . '"></label>
									</div>'
                                        : '<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input shortcode-notify" id="customSwitch' . $post->id . '" data-shortcode="' . $post->identifier . '">
                                        <label class="custom-control-label" for="customSwitch' . $post->id . '" data-shortcode="' . $post->identifier . '">Activate</label>
									</div>';

                                    $nestedData['creator']      = optional($post->user)->name;
                                    $nestedData['date_created'] = $post->created_at->toDayDateTimeString();
                                    $nestedData['action']       = $btn;
                                    $data[]                     = $nestedData;
                                    $pos++;
                                }
                        }

                    $json_data = ['draw' => (int)$request->input('draw'), 'recordsTotal' => $totalData, 'recordsFiltered' => $totalFiltered, 'data' => $data];

                    return $json_data;
                }

            private function button($post, $request)
                {

                    $button = null;
                    if ($request->user()->can('edit_payment_method'))
                        {
                            $button .= '<a class="text text-dark" href="' . route('user.edit', $post->id) . '" data-toggle="tooltip" title="Edit User">
                                                <i class="fas fa-edit"></i> Edit
                                                </a>';
                        }
                    if ($request->user()->can('destroy_payment_method'))
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
