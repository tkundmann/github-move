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

elixir.config.sourcemaps = false;

elixir(function(mix) {
  mix.less('util.less', 'public/css');
  mix.less('main.less', 'public/css');
  mix.less('header.less', 'public/css');
  mix.less('menu.less', 'public/css');
  mix.less('pickup_request.less', 'public/css');
  mix.less('admin_reports.less', 'public/css');
});
