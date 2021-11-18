

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

 const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css').version() //version()-只要文件修改，哈希值就会变，提醒客户端需要重新加载文件,解决缓存问题
    .copyDirectory('resources/editor/js', 'public/js')    //编辑器 js
   .copyDirectory('resources/editor/css', 'public/css');  //编辑器css
