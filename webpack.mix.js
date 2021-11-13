const { js } = require('laravel-mix');
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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/pagescript/profile_update.js','public/js')
    .js('resources/js/pagescript/dashboard.js','public/js')
    .js('resources/js/pagescript/course_show.js','public/js')
    .js('resources/js/pagescript/course_edit.js','public/js')
    .js('resources/js/pagescript/forum_show.js','public/js')
    .js('resources/js/pagescript/forum_edit.js','public/js')
    .js('resources/js/pagescript/post_edit.js','public/js')
    .js('resources/js/pagescript/transaction.js','public/js')

    .postCss('resources/css/app.css', 'public/css', [
        //
    ])
    .sass('resources/sass/app.scss','public/css')
    .sass('resources/sass/course.scss', 'public/css')
    .sass('resources/sass/course_edit.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css')
    .sass('resources/sass/auth.scss', 'public/css')

