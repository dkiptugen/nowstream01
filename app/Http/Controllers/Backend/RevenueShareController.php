<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RevenueShare;
use App\Traits\Meta;
use Illuminate\Http\Request;

class RevenueShareController extends Controller
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
    public function show(RevenueShare $revenueShare)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RevenueShare $revenueShare)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RevenueShare $revenueShare)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RevenueShare $revenueShare)
    {
        //
    }
}
