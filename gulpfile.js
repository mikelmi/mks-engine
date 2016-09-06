const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var path = {
    node: 'node_modules/',
    node_js: '../../../node_modules/'
};

elixir(function(mix) {
    mix.sass('bootstrap.scss');

    mix.scripts([
        path.node_js + 'jquery/dist/jquery.js',
        path.node_js + 'tether/dist/js/tether.js',
        path.node_js + 'bootstrap/dist/js/bootstrap.js'
    ], 'public/js/bootstrap.js');
});
