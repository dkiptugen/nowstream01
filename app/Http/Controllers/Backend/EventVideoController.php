<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Event;
use App\Traits\Meta;
use Illuminate\Http\Request;

class EventVideoController extends Controller
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
        public function index(Event $event)
            {
                $this->data['event'] = $event;
                return view('Backend.modules.event.videos.index', $this->data);
            }

    /**
     * Show the form for creating a new resource.
     */
        public function create(Event $event)
            {
                $this->data['event'] = $event;
                return view('Backend.modules.event.videos.add', $this->data);
            }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request, Event $event)
            {
                //
            }

    /**
     * Display the specified resource.
     */
        public function show(Event $event, Content $video)
            {
                $this->data['event'] = $event;
                $this->data['video'] = $video;
                return view('Backend.modules.event.videos.show', $this->data);
            }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(Event $event, Content $video)
            {
                $this->data['event'] = $event;
                $this->data['video'] = $video;
                return view('Backend.modules.event.videos.show', $this->data);
            }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Event $event, Content $video)
            {
                //
            }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(Event $event, Content $video)
            {
                //
            }
        public function datatable(Event $event, Request $request)
            {

            }
    }
