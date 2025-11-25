<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\LogDatatable;
use App\Models\Activity;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogsController extends Controller
    {
		/**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View|string
         */
        public function index()
            {
                //$this->data['active'] = $this->active_product();
                return view('Backend.modules.logs.index',$this->data);
            }
        public function datatable(Request $request, LogDatatable $datatable)
            {
                $datatable->columns = array(
                    1   =>  'description',
                    2   =>  'causer_id',
                    3   =>  'subject_type',
                    4   =>  'subject_id',
                    5   =>  'properties',
                    6   =>  'created_at'

                );
                return response()->json($datatable->data($request));

            }

    }
