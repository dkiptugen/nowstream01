<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Datatables\ChannelSubscriptionDatatable;
use App\Models\Channel;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('Backend.modules.subscriptions.index',$this->data);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Custom method added for datatable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request, ChannelSubscriptionDatatable $datatable)
    {
        $datatable->columns = [0=>'uuid'];
        return response()->json($datatable->data($request));
    }

}
