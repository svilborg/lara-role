<?php
use App\Http\Middleware\Role;
use App\Http\Middleware\Resource;

// route to show the login form

Route::get('/login', [
    'uses' => 'LoginController@index'
    // No acl -> Public
]);

Route::post('/login', [
    'uses' => 'LoginController@login'
    // No acl -> Public
]);

Route::get('/logout', [
    'uses' => 'LoginController@logout'
]);

Route::group([
    'middleware' => [
        Role::class
    ]
], function () {

    Route::get('/home', [
        'uses' => 'HomeController@index',
        'acl' => [
            'role' => [
                'admin',
                'viewer',
                'user'
            ]
        ]
    ]);

    Route::get('/items', [
        'uses' => 'ItemsController@index',
        'acl' => [
            'role' => [
                'admin',
                'viewer'
            ]
        ]
    ]);

    Route::get('/items', [
        'uses' => 'ItemsController@delete',
        'acl' => [
            'role' => [
                'admin'
            ]
        ]
    ]);
});