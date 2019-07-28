const mix = require('laravel-mix');
const BrowsersSupport = require("./config/browserslistrc.js");

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

mix.setPublicPath('public')

/**
 * Javascript
 * Compiles all JS to public
 * 
 */
mix.js('src/js/app.js', 'public/js/app.js')
    .js('src/js/pages/home.js', 'public/js/pages/home.js')

/**
 * SCSS
 * Compiles all SCSS files to public and uses Sass lint.
 * 
 */
mix.sass('src/scss/master.scss', 'public/css/app.css')

/**
 * Production
 * Uses prettier plugin, minifies & babel for JS.
 * 
 */
if (mix.inProduction()) {
    mix.babel('public/js/app.js', 'public/js/app.js')
        .babel('src/js/pages/home.js', 'public/js/pages/home.js')
    mix.options({
        autoprefixer: {
            options: {
                overrideBrowserslist: BrowsersSupport.overrideBrowserslist
            }
        }
    })
}