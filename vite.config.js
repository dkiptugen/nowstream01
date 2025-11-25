import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import inject from '@rollup/plugin-inject';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    resolve: {
        alias: {
            jquery: path.resolve(__dirname, 'node_modules/jquery/dist/jquery.js'),
           // simplebar: path.resolve(__dirname, 'node_modules/simplebar/dist/simplebar.esm.js'),
        },
    },
    plugins: [
        // Laravel plugin handles JS & SCSS entry points and HMR
        laravel({
            input: [
                'resources/js/app.js',
                'resources/scss/app.scss',
            ],
            refresh: true,
        }),

        // Provide jQuery globally (like Webpack ProvidePlugin)
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),

        // Copy fonts & plugins (like Mix .copy)
        viteStaticCopy({
            targets: [
                { src: 'node_modules/@fortawesome/fontawesome-free/webfonts/*', dest: 'webfonts' },
                { src: 'node_modules/summernote/dist/font/*', dest: 'css/font' },
                { src: 'node_modules/summernote/dist/plugin/*', dest: 'plugin' },
            ],
        }),
    ],

    build: {
        outDir: 'public/backend_assets', // Match your old Mix output
        rollupOptions: {
            output: {
                entryFileNames: 'app.js', // single JS output
                chunkFileNames: '[name].js', // optional: other chunks
                // CSS goes to backend_assets/css
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.endsWith('.css')) {
                        return 'css/[name][extname]';
                    }
                    // Fonts go to backend_assets/webfonts
                    if (assetInfo.name && /\.(woff2?|ttf|eot|svg)$/.test(assetInfo.name)) {
                        return 'webfonts/[name][extname]';
                    }
                    return '[name][extname]';
                },
            },
        },
    },
});
