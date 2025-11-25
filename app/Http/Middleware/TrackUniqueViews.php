<?php
    
    namespace App\Http\Middleware;
    
    use App\Models\PageView;
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Session;
    use Symfony\Component\HttpFoundation\Response;
    
    class TrackUniqueViews
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
         */
        public function handle(Request $request, Closure $next)
        : Response {
            $userId = auth()->id();                         // Assuming you're using Laravel's built-in authentication
            $pageId = $request->route()->parameter('page'); // Get the page ID from route parameters
            
            $viewKey = 'page_'.$pageId.'_viewed';
            
            if (!Session::has($viewKey)) {
                // Increment view count
                PageView::create([
                    'user_id' => $userId, 'page_id' => $pageId,
                ]);
                
                // Mark page as viewed in session
                Session::put($viewKey, true);
            }
            
            return $next($request);
        }
    }
