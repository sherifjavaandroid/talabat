// const mix = require('laravel-mix');
// require('laravel-mix-purgecss');

// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel applications. By default, we are compiling the CSS
//  | file for the application as well as bundling up all the JS files.
//  |
//  */

// const tailwindcss = require('tailwindcss')

// mix.js("resources/js/app.js", "public/js")
//     .sass("resources/sass/app.scss", "public/css")
//     .purgeCss()
//     .options({
//         processCssUrls: false,
//         postCss: [tailwindcss("tailwind.config.js")]
//     });

const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
require('laravel-mix-purgecss');

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

mix.js('resources/js/app.js', 'public/js')
    .purgeCss()
    .sass("resources/sass/app.scss", "public/css")
    .postCss('resources/css/app.css', 'public/css', [
        //
        require('tailwindcss'),
    ]);
//
mix.copy('node_modules/intl-tel-input/build/js/utils.js', 'public/vendor/intl-tel-input/build/js');
