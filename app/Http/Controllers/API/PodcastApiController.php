<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\WatchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PodcastApiController extends Controller
    {

        private const CACHE_TTL = 600; // 10 minutes

    /**
     * List Podcasts
     */
        public function index(Request $request)
            {

            }

    /**
     * Single Podcast
     */
        public function show($slug)
            {

            }

    /**
     * Podcast Episodes
     */
        public function episodes($slug)
            {

            }

    /**
     * Record Watch History
     */
        public function recordWatchHistory(Request $request)
            {

            }

    /* ---------------- Helpers ---------------- */

        private function serializePodcast(Content $podcast)
            {

            }

        private function serializeEpisode(Content $episode, Content $podcast)
            {

            }

        private function episodePlaybackUrl(Content $episode)
            {

            }

        private function assetUrl(?string $path): ?string
            {

            }

        private function sanitizeString($value)
            {

            }

        private function jsonPaginatedResponse($paginator, $transform)
            {

            }
    }
