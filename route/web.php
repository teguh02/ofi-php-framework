<?php

use vendor\OFI_PHP_Framework\Controller\Route;

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

    /**
     * Index (Welcome) Route
     */

    -> route(4) 
       -> url('/') 
       -> type('controller') 
       -> to('indexController@view') 
       -> method('GET')

    # Start write your route here

    // Your code

-> end();

