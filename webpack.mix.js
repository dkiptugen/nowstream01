const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

let webpack =   require('webpack');
let path    =   require('path');

mix.webpackConfig({
	                  resolve: {
		                  alias: {
							  jquery: path.resolve(__dirname, 'node_modules/jquery/dist/jquery.js'),
			                  'cropperjs': path.resolve(__dirname, 'node_modules/cropperjs')
						  }
	                  },
	                  plugins: [
		                  // ProvidePlugin helps to recognize $ and jQuery words in code
		                  // And replace it with require('jquery')
		                  new webpack.ProvidePlugin({
			                                            $: 'jquery',
			                                            jQuery: 'jquery'
		                                            })
	                  ],
	                  devtool: 'source-map',               // For easier debugging
                  });
mix.js('resources/js/app.js', 'public/backend_assets/js')
   .sass('resources/scss/app.scss', 'public/backend_assets/css')
    .sass('resources/scss/dark.scss', 'public/backend_assets/css')
   .options({
	            processCssUrls: false
            })
   .copy('node_modules/@fortawesome/fontawesome-free/webfonts','public/backend_assets/webfonts/')
   .copy('node_modules/summernote/dist/font','public/backend_assets/css/font')
   .copy('node_modules/summernote/dist/plugin','public/backend_assets/plugin/')
   .version();

