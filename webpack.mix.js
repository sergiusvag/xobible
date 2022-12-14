const mix = require("laravel-mix");

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

mix.js("resources/js/app.js", "public/js")
    .js("resources/js/app-online-room.js", "public/js")
    .js("resources/js/app-online-color-picker.js", "public/js")
    .js("resources/js/app-offline-color-picker.js", "public/js")
    .js("resources/js/app-online-game.js", "public/js")
    .js("resources/js/app-offline-game.js", "public/js")
    .vue({ version: 3 })
    .sass("resources/sass/app.scss", "public/css");
