<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\StreamDatatable;
use App\Http\Datatables\TvDatatable;
use App\Models\Content;
use App\Traits\Meta;
use Illuminate\Http\Request;

class TvController extends Controller
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
                return view('Backend.modules.tv.index', $this->data);

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
        public function show(Content $tv)
            {
                $this->data['tv'] = $tv;
                return view('Backend.modules.tv.show', $this->data);
            }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Content $tv)
            {
                //
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Content $tv)
            {
                //
            }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Content $tv)
            {
                //
            }


    /**
     * Custom method added for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function datatable(Request $request, TvDatatable $datatable)
            {
                $datatable->columns = [0 => 'uuid',1=>'title',2=>"description"];
                return response()->json($datatable->data($request));
            }
}
