<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\VideoDatatable;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(Video $streamVideo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $streamVideo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $streamVideo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $streamVideo)
    {
        //
    }
        public function datatable(Request $request,VideoDatatable $datatable)
            {
                $datatable->columns = [1=>'title'];
                return response()->json($datatable->data($request));
            }
}
