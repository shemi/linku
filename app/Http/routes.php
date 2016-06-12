<?php

use Linku\Models\Folder;


Route::get('/', function () {
    return view('welcome');
});

Route::auth();

// Authentication Routes...
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

// Registration Routes...
Route::get('register/{inviteToken?}', 'Auth\AuthController@showRegistrationForm');
Route::post('register/{inviteToken?}', 'Auth\AuthController@register');

// Password Reset Routes...
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');

//Route::get('/home', [
//    'uses' => 'HomeController@index',
//    'as'   => 'home',
//]);

/* Links Routes */
//Route::resource('link', 'LinkController', []);
//Route::resource('folder', 'FolderController', []);

//Route::get('share/{folder}', function (Folder $folder) {
//    $share = new \Linku\Models\Share();
//    $share->user_id = 2;
//    $share->by_user_id = 1;
//    $share->permissions = 7;
//
//    $folder->shares()->save($share);
//
//    return redirect()->back();
//});