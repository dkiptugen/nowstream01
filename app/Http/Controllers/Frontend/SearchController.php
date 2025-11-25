<?php
	
	namespace App\Http\Controllers\Frontend;
	
	use App\Models\Video;
	use App\Models\Stream;
	use App\Models\Channel;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	
	class SearchController extends Controller
		{
			public function search(Request $request)
				{
					$query = $request->input('query');
					
					$channels = Channel::where('name', 'like', "%$query%")->take(5)->get();
					$streams  = Stream::where('title', 'like', "%$query%")->take(5)->get();
					$videos   = Video::where('title', 'like', "%$query%")->take(5)->get();
					
					return view('Frontend.search', compact('channels', 'streams', 'videos'))->render();
				}
			
			
		}
