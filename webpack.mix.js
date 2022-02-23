const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
    }
});

mix.webpackConfig({
    output: {
        filename: '[name].js',
        chunkFilename: 'modules/[name]/component.js'
    },
});

//mix.js('resources/js/vue.js', 'public/js/vue-develop.js').vue();
mix.js('resources/js/vue2.js', 'public/js/vue2.js').vue();
