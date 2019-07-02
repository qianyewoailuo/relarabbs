<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     echo 'helloworld';
// });

Route::get('/','PagesController@root')->name('root');

// Auth::routes(); // 等价于下面的4部分路由,为了直观使用下面路由

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
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// Route::get('/home', 'HomeController@index')->name('home');  // 主页路由已自定义,不在需要

// User 相关路由
Route::resource('users', 'UsersController',['only'=>['show','update','edit']]);
// 相当于如下路由
// Route::get('/users/{user}','UsersController@show')->name('users.show');
// Route::get('/users/{user}/edit','UsersController@edit')->name('users.edit');
// Route::patch('/users/{user}','UsersController@update')->name('users.update');   // 这里的patch也可以用put代替

Route::resource('topics', 'TopicsController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);

// 话题图片上传路由
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);
// 等价于下面
// Route::get('categories','CategoriesController@show')->name('categories.show');
