<?php

use ofi\ofi_php_framework\Route\Route;
use App\Controllers\indexController;
use App\Controllers\Auth\authController;

Route::auto(true);

Route::get('get-method', function() {
    d($GLOBALS);
}, ['name' => 'get-method']);

Route::post('post-method', function() {
    d($GLOBALS);
});

Route::delete('delete-method', function() {
    d($GLOBALS);
});

Route::put('put-method', function() {
    d($GLOBALS);
});

Route::any('any-method', function() {
    d($GLOBALS);
});

Route::any('apapun', function() {
    echo 'any';
}, ['middleware' => [\App\Middleware\auth\auth::class, 'check']]);

Route::get('/', [indexController::class, 'view'], ['name' => 'welcomePage']);
Route::get('/login', [authController::class, 'login'], ['name' => 'loginPage']);
Route::get('/register', [authController::class, 'register'], ['name' => 'registerPage']);