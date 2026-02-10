<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\PaymentMethodDatatable;
use App\Models\PaymentMethod;
use App\Traits\Meta;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentMethod;
use App\Http\Requests\UpdatePaymentMethod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class PaymentMethodController extends Controller
{

        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function index()
            {

                return view('Backend.modules.payment_methods.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
        public function create()
            {
                return view('Backend.modules.payment_methods.add', $this->data);
            }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePaymentMethod $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function store(StorePaymentMethod $request)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        try
                            {
                                $method                         = new PaymentMethod();
                                $method->name                   = $request->name;
                                $method->identifier             = Str::ulid();
                                $method->provider               = $request->provider;
                                $method->type                   = $request->type;
                                $method->configuration          = $request->configuration;
                                $method->status                 = 1;
                                $method->notifying              = $request->notify;
                                $method->notification_endpoints = explode(',', $request->notification_endpoint);
                                $method->system_user_id                = Auth::user()->id;
                                $res                            = $method->save();
                                if ($res)
                                    {

                                        return self::success('Payment Method', 'Added successfully', route('payment_method.index'));
                                    }

                                return self::failed('Payment Method', 'failed to create', route('payment_method.index'));
                            }
                        catch (Exception $e)
                            {
                                return self::failed('Payment Method', $e->getMessage(), route('payment_method.index'));
                            }

                    }

                return self::failed('Payment Method', $validateddata, route('payment_method.index'));
            }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\PaymentMethod $mode
     *
     * @return \Illuminate\Http\Response
     */
        public function show(PaymentMethod $mode)
            {
                //
            }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\PaymentMethod $mode
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response|\Illuminate\View\View
     */
        public function edit(PaymentMethod $mode, $id)
            {

                $this->data['payment_method'] = $mode->find($id);

                //dd($mode->find($id)->configuration);
                return view('Backend.modules.payment_methods.edit', $this->data);
            }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PaymentMethod $mode
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function update(UpdatePaymentMethod $request, PaymentMethod $mode, $id)
            {

                $validateddata = $request->validated();
                if ($validateddata)
                    {
                        try
                            {
                                $method       = $mode->find($id);
                                $method->name = $request->name;
                                //$method->identifier             = Str::ulid();
                                $method->provider               = $request->provider;
                                $method->type                   = $request->type;
                                $method->configuration          = $request->configuration;
                                $method->status                 = 1;
                                $method->notifying              = $request->notify;
                                $method->notification_endpoints = explode(',', $request->notification_endpoint);
                                $method->system_user_id                 = Auth::user()->id;
                                $res                            = $method->save();
                                if ($res)
                                    {

                                        return self::success('Payment Method', 'Added successfully', route('payment_method.index'));
                                    }

                                return self::failed('Payment Method', 'failed to create', route('payment_method.index'));
                            }
                        catch (Exception $e)
                            {
                                return self::failed('Payment Method', $e->getMessage(), route('payment_method.index'));
                            }

                    }

                return self::failed('Payment Method', $validateddata, route('payment_method.index'));
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PaymentMethod $mode
     *
     * @return \Illuminate\Http\Response
     */
        public function destroy(PaymentMethod $mode)
            {
                //
            }

        public function datatable(Request $request,PaymentMethodDatatable $datatable): \Illuminate\Http\JsonResponse
            {
                $datatable->columns = ['id', 'name', 'period', 'cost', 'product_id', 'startdate', 'enddate', 'author', 'status'];
                return response()->json($datatable->data($request));

            }
}
