<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;


class PodcastApiController extends Controller
    {
        public function index()
            {
                return response()->json(['message' => 'Hello from API!']);
            }
        public function show()
            {
                return response()->json(['message' => 'Hello from API!']);
            }
    }
