const mix = require('laravel-mix');
const path = require('path');
const FileManagerPlugin = require('filemanager-webpack-plugin');

const isProduction = mix.inProduction();

/*
 |--------------------------------------------------------------------------
 | Webpack Custom Config
 |--------------------------------------------------------------------------
 */
mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.scss'],
        alias: {
            cropperjs: path.resolve(__dirname, 'node_modules/cropperjs'),
            request$: 'xhr'
        }
    },

    plugins: [
        ...(isProduction ? [
            new FileManagerPlugin({
                events: {
                    onEnd: {
                        copy: [
                            { source: './public/backend_assets/', destination: './static' }
                        ]
                    }
                }
            })
        ] : [])
    ],

    devtool: isProduction ? 'source-map' : 'inline-source-map',

    output: {
        chunkFilename: 'backend_assets/js/[name].js'
    },

    performance: {
        hints: false
    },

    module: {
        rules: [
            {
                test: /\.(woff(2)?|ttf|eot|svg)(\?.*)?$/,
                type: 'backend_asset/resource',
                generator: {
                    filename: 'backend_assets/fonts/[name][ext]'
                }
            },
            {
                test: /\.(png|jpg|jpeg|gif)(\?.*)?$/,
                type: 'backend_asset/resource',
                generator: {
                    filename: 'backend_assets/img/[name][ext]'
                }
            }
        ]
    }
});

/*
 |--------------------------------------------------------------------------
 | Main Assets
 |--------------------------------------------------------------------------
 */
mix.js('resources/js/app.js', 'public/backend_assets/js')
    .sass('resources/scss/app.scss', 'public/backend_assets/css')
    .options({
        processCssUrls: false,
        postCss: [
            require('autoprefixer'),
        ]
    });

/*
 |--------------------------------------------------------------------------
 | Copy Assets
 |--------------------------------------------------------------------------
 */
mix.copyDirectory('resources/fonts', 'public/backend_assets/fonts');


mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/backend_assets/webfonts');

/*
 |--------------------------------------------------------------------------
 | Source Maps
 |--------------------------------------------------------------------------
 */
mix.sourceMaps(!isProduction, isProduction ? 'source-map' : 'inline-source-map');

/*
 |--------------------------------------------------------------------------
 | Versioning
 |--------------------------------------------------------------------------
 */
if (isProduction) {
    mix.version();
}
