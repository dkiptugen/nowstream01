<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\RadioDatatable;
use App\Models\Content;
use App\Traits\Meta;
use Illuminate\Http\Request;

class RadioController extends Controller
{
        use Meta;
        public $data = [];
        public function __construct()
            {
                $this->data = self::product_def();
            }
    /**
     * Display a listing of the resource.
     */
        public function index()
            {
                return view('Backend.modules.radio.index', $this->data);

            }

    /**
     * Show the form for creating a new resource.
     */
        public function create()
            {
                //
            }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
            {
                //
            }

    /**
     * Display the specified resource.
     */
        public function show(Content $radio)
            {
                $this->data['radio'] = $radio;
                return view('Backend.modules.radio.show', $this->data);
            }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Content $radio)
            {
                //
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Content $radio)
            {
                //
            }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Content $radio)
            {
                //
            }


    /**
     * Custom method added for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function datatable(Request $request, RadioDatatable $datatable)
            {
                $datatable->columns = [0 => 'uuid',1=>'title',2=>"description"];
                return response()->json($datatable->data($request));
            }
}
