<?php

use ofi\ofi_php_framework\Controller\route as Route;

/**
 * You can define your application routes here
 * Please don't remove Route::start and -> end() or you can damage 
 * our system
 * 
 * Explanation :
 * 1. route(Number) same as array index start from 0
 * 2. url(string) is your route
 * 3. type(string) is your route type (view or controller)
 * 4. to(string) is the destination from your route
 * 5. method(string) is your route HTTP Method (Only Get or POST)
 */

Route::start()

    /**
     * Auth Route
     */
    -> route(0) -> url('/login') -> type('controller') -> to('Auth\authController@login')
    -> route(1) -> url('/home') -> type('controller') -> to('Auth\homeController@home')
    -> route(2) -> url('/register') ->  type('controller') -> to('Auth\authController@register')
    -> route(3) -> url('/logout') ->  type('controller') -> to('Auth\authController@logout') -> method('POST')

    // For example Route with middleware 
    -> route(4) -> url('/middleware') 
    -> type('controller') 
    -> to('Auth\authController@register') 
    -> middleware('auth\auth@check') 

    /**
     * Index (Welcome) Route
     */

    -> route(5) 
       -> url('/') 
       -> type('view') 
       -> to('index') 
       -> method('GET')

    # Start write your route here

    // Your code

-> end();

