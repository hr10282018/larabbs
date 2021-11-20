<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/403', function () {
//   abort(403, '抱歉，你没有权限访问！');
// });


Route::get('/', 'PagesController@root')->name('root');
Route::get('/dd', 'PagesController@root2')->name('root'); // 查看php版本

/* Auth::routes();  为了直观，用下面四个功能的路由(用户身份验证、注册、密码重置、Eamil认证)替换这一句
  此处是Laravel的用户认证路由,在vendor/laravel/ui/src/AuthRouteMethods.php 中即可找到定义的地方，以上等同于：
*/
// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update'); //用户重置密码
// Email认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


//
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
/**上面代码将等同于如下
Route::get('/users/{user}', 'UsersController@show')->name('users.show'); 显示用户个人信息页面
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit'); 显示编辑个人资料页面
Route::patch('/users/{user}', 'UsersController@update')->name('users.update'); 处理 edit 页面提交的更改
**/


//Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
// 将show单独拿出来（话题展示），路由加上slug参数，?表示该参数可有可不有，为了兼容该表的slug字段为空
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');


Route::resource('categories', 'CategoriesController', ['only' => ['show']]);//分类列表显示

Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');// 文本编辑-上传图片


Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);