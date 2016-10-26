var gulp = require('gulp');
const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

var lessToScss = require('gulp-less-to-scss');


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

function path_node(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_node);
    }

    return 'node_modules/' + (path||'');
}

function path_node_rel(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_node_rel);
    }

    return '../../../node_modules/' + (path||'');
}

function path_bower(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_bower);
    }

    return 'bower_components/' + (path||'');
}

function path_bower_rel(path) {
    if (Object.prototype.toString.call(path) === '[object Array]') {
        return path.map(path_bower_rel);
    }

    return '../../../bower_components/' + (path||'');
}

elixir.extend('lessToSass', function(source, dist) {
    new elixir.Task('lessToSass', function() {
        return gulp.src(source)
            .pipe(lessToScss())
            .pipe(gulp.dest(dist));
    });

});

elixir(function(mix) {

    mix.lessToSass(
        path_node('ekko-lightbox/ekko-lightbox.less'),
        elixir.config.assetsPath + '/sass'
    );

    mix.sass('bootstrap.scss');

    mix.scripts(path_node_rel(
        [
            'jquery/dist/jquery.js',
            'tether/dist/js/tether.js',
            'bootstrap/dist/js/bootstrap.js',
            'ekko-lightbox/dist/ekko-lightbox.js',
            'jquery-form/jquery.form.js'
        ]).concat(['bootstrap.js']),
        'public/js/bootstrap.js');

    //font-awesome
    mix.copy([
        path_node('font-awesome/fonts')
    ], 'public/fonts');

    /** Backend **/
    mix.styles([
        path_node_rel('angular-ui-tree/dist/angular-ui-tree.css'),
        'admin/*.css'
    ], 'public/admin/css/admin.css');

    mix.scripts([
        path_node_rel('angular-ui-tree/dist/angular-ui-tree.js'),
        'admin/*.js'
    ], 'public/admin/js/admin.js');

    /** FileManager **/
    mix.styles([
        path_bower_rel('bootswatch/flatly/bootstrap.css'),
        path_bower_rel('angular-filemanager/dist/angular-filemanager.min.css'),
        'filemanager.css'
    ], 'public/filemanager/css/app.css');

    mix.scripts([
        path_bower_rel('jquery/dist/jquery.js'),
        path_bower_rel('bootstrap/dist/js/bootstrap.js'),
        path_bower_rel('angular/angular.js'),
        path_bower_rel('angular-translate/angular-translate.js'),
        path_bower_rel('ng-file-upload/ng-file-upload.js'),
        path_bower_rel('angular-filemanager/dist/angular-filemanager.min.js'),
        'filemanager.js'
    ], 'public/filemanager/js/app.js');

    mix.copy([
        path_bower('bootstrap/dist/fonts')
    ], 'public/filemanager/fonts');
});
