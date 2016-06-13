<?php

Route::group([
    'middleware' => 'cors',
    'prefix'     => 'api/v1',
    'namespace'  => 'Api',
], function () {

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@postLogin');
        Route::post('register', 'AuthController@postRegister');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('app/status', 'StatusController@index');

        Route::resource('folder', 'FolderController', []);

        Route::resource('link', 'LinkController', ['except' => ['index', 'create', 'show', 'edit']]);

        Route::resource('share', 'ShareController', ['except' => ['index', 'show', 'create', 'edit']]);
        Route::post('share/invite', 'ShareController@inviteAndShare');
    });

});