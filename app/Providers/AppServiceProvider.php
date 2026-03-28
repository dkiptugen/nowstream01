<?php
	
	namespace App\Providers;
	
	use App\Services\MerchCartService;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Config;
	use Illuminate\Support\Facades\URL;
	use Illuminate\Support\ServiceProvider;
	use Illuminate\Support\Facades\View;
	
	
	class AppServiceProvider extends ServiceProvider
		{
		/**
		 * Register any application services.
		 */
			public function register()
			: void
				{
					//
				}
		
		/**
		 * Bootstrap any application services.
		 */
			public function boot()
			: void
				{
					if (config('app.env') !== 'local')
						{
							URL::forceScheme('https');
						}

					View::composer('Frontend.includes.header', function ($view)
						{
							$cartCount = 0;

							if (Auth::check())
								{
									$cartService = app(MerchCartService::class);
									$cart        = $cartService->getCart(Auth::user());
									$summary     = $cartService->cartSummary($cart);
									$cartCount   = (int) $summary['items']->sum('quantity');
								}

							$view->with('headerCartCount', $cartCount);
						});
				}
		}
