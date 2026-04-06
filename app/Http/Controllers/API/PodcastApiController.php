<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PodcastApiController extends Controller
    {
        public function index()
            {
                return response()->json(['message' => 'Hello from API!']);
            }
        public function show(Request $request, $slug)
            {
                return response()->json(['message' => 'Hello from API!']);
            }
    }
