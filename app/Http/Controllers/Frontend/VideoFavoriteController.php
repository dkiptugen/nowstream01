<?php
	
	namespace App\Http\Controllers\Frontend;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use App\Models\Video;
	use App\Http\Controllers\Controller;
	
	class VideoFavoriteController extends Controller
		{
			public function favorite($videoId)
				{
					$user  = Auth::user();
					$video = Video::findOrFail($videoId);
					
					$user->favoriteVideos()->attach($video->id);
					
					return response()->json(['message' => 'Video added to favorites!']);
				}
			
			public function myfavorite()
				{
					$user   = Auth::user();
					$videos = $user->favoriteVideos;
					
					return view('Frontend.modules.videos.favorite', compact('videos'));
				}
			
			public function unfavorite($videoId)
				{
					$user  = Auth::user();
					$video = Video::findOrFail($videoId);
					
					$user->favoriteVideos()->detach($video->id);
					
					return response()->json(['message' => 'Video removed from favorites!']);
				}
		}
