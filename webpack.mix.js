const { mix } = require('laravel-mix');

const publicPath = 'public';

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery'],
    'tether': ['window.Tether', 'Tether'],
});

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
mix.js(asset('js/system-light.js'), public_path('js/system-light.js'));

//frontend system js with bootstrap
mix.js(asset('js/system.js'), public_path('js/system.js'));

//TODO: move to combined version
mix.copy(asset('js/system.js'), public_path('js/app.js'));

//font-awesome
mix.copy(path_node('font-awesome/fonts'), public_path('fonts'));


/** Backend **/
mix.combine([
    path_node('angular-ui-tree/dist/angular-ui-tree.css'),
    asset('css/admin/*.css')
], public_path('admin/css/admin.css'));

mix.js(asset([
    'js/admin/mks-admin-ext.js',
    'js/admin/artisan.js',
    'js/admin/dashboard.js',
    'js/admin/category-manager.js',
    'js/admin/menu-manager.js',
    'js/admin/widget-manager.js'
]), public_path('admin/js/admin-app.js'));

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

//CKEDITOR plugins
mix.copy(asset('ckeditor/plugins'), public_path('vendor/ckeditor/plugins'), false);

