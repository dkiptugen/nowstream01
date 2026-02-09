<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use App\Traits\Meta;
use Illuminate\Http\Request;

class OrganizerController extends Controller
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
    public function show(Organizer $organizer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organizer $organizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organizer $organizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organizer $organizer)
    {
        //
    }
}
