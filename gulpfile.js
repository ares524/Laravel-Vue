var elixir = require('laravel-elixir');

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
elixir(function (mix) {
    mix.scripts([
        'vendor/js/bootstrap.min.js',
        'vendor/js/jquery.min.js',
        'vendor/js/jquery.nicescroll.min.js',
        'vendor/js/socket.io.min.js',
    ], 'public/js/app.js')
});
elixir(function(mix) {
    mix.browserify('app.js');
    // mix.sass('app.scss');
});
