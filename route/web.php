<?php

use ofi\ofi_php_framework\Route\Route;
use App\Controllers\indexController;
use App\Controllers\Auth\authController;

Route::auto(true);

Route::get('get-method', function() {
    echo 'GET method test';
}, ['name' => 'get-method']);

Route::post('post-method', function() {
    echo 'Post method test';
});

Route::delete('delete-method', function() {
    echo 'Delete method test';
});

Route::put('put-method', function() {
    echo 'PUT Method test';
});

Route::any('any-method', function() {
    echo 'ANY Method test';
});

Route::any('apapun', function() {
    echo 'any';
}, ['middleware' => [\App\Middleware\auth\auth::class, 'check']]);

Route::get('/', [indexController::class, 'view'], ['name' => 'welcomePage']);
Route::get('/login', [authController::class, 'login'], ['name' => 'loginPage']);
Route::get('/register', [authController::class, 'register'], ['name' => 'registerPage']);