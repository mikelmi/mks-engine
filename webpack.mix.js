const { mix } = require('laravel-mix');

const publicPath = 'public';

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

function path_node(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_node);
    }

    return 'node_modules/' + (path||'');
}

function path_bower(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_bower);
    }

    return 'bower_components/' + (path||'');
}

function public_path(path) {
    return publicPath + '/' + path;
}

function asset(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(asset);
    }

    return 'resources/assets/' + (path||'');
}

//general base styles
mix.sass(asset('sass/system-light.scss'), public_path('css'));
mix.sass(asset('sass/system.scss'), public_path('css'));

//frontend system js without bootstrap
mix.js(path_node(
    [
        'jquery/dist/jquery.js',
        'jquery-form/jquery.form.js',
        'bootstrap-notify/bootstrap-notify.js'
    ]),
    public_path('js/system-light.js')
);

//frontend system js with bootstrap
mix.js(path_node(
    [
        'jquery/dist/jquery.js',
        'tether/dist/js/tether.js',
        'bootstrap/dist/js/bootstrap.js',
        'ekko-lightbox/dist/ekko-lightbox.js',
        'jquery-form/jquery.form.js',
        'bootstrap-notify/bootstrap-notify.js'
    ]),
    public_path('js/system.js')
);

//font-awesome
mix.copy(path_node('font-awesome/fonts'), public_path('fonts'));


/** Backend **/
mix.combine([
    path_node('angular-ui-tree/dist/angular-ui-tree.css'),
    asset('css/admin/*.css')
], public_path('admin/css/admin.css'));

mix.js([
    path_node('angular-ui-tree/dist/angular-ui-tree.js'),
    asset('js/admin/artisan.js'),
    asset('js/admin/category-manager.js'),
    asset('js/admin/dashboard.js'),
    asset('js/admin/menu-manager.js'),
    asset('js/admin/mks-admin-ext.js'),
    asset('js/admin/widget-manager.js')
], public_path('admin/js/admin.js'));

/** FileManager **/
mix.combine([
    path_bower('bootswatch/flatly/bootstrap.css'),
    path_bower('angular-filemanager/dist/angular-filemanager.min.css'),
    asset('css/filemanager.css')
], public_path('filemanager/css/app.css'));

mix.js([
    path_bower('jquery/dist/jquery.js'),
    path_bower('bootstrap/dist/js/bootstrap.js'),
    path_bower('angular/angular.js'),
    path_bower('angular-translate/angular-translate.js'),
    path_bower('ng-file-upload/ng-file-upload.js'),
    path_bower('angular-filemanager/dist/angular-filemanager.min.js'),
    asset('js/filemanager.js')
], public_path('filemanager/js/app.js'));

mix.copy(path_bower('bootstrap/dist/fonts'), public_path('filemanager/fonts'));
