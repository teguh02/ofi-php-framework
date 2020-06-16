<?php

/**
 * URL Declaration in array
 * HTTP Method Support GET and POST only, this optional, you can write method or not.
 * The default HTTP Method is GET, if you not write HTTP Method  (See the sample).
 * 
 *  Kinds of Type :
 *    1. controller  is redirect to a controller (use @ for select a method)
 *    2. url         is redirect to a URL. For example you can write like this www.google.com
 * 
 * Now you can auto detect controller from url
 * 
 * For example from this url :
 * http://localhost:9000/index/view
 * 
 * You can call indexController (inside Controllers folder)
 * and call 'view' method
 * 
 * Note :  > http://localhost:9000/ is your app url
 *         > index is the controller file and class name 
 *            
 *           Example :
 *            # index = indexController.php
 *            # home = homeController.php
 *            # /base/auth = /base/authController.php
 * 
 *         > view is methods name what do you want to call
 * 
 *            public function view() {
 *              // your code
 *            }
 * 
 * For this url : 
 * http://localhost:9000/Auth/home/index
 * 
 * Homecontroller are located in 'Auth folder'
 *
 * Tips! If you need you can delete example route
 */

$route = [
    // Auth Route Declaration
    // you can remove comment tag to turn on this route

    ['url' => "login",'type' => 'controller','to' => 'Auth\authController@login',],
    ['url' => "register",'type' => 'controller','to' => 'Auth\authController@register',],
    // ['url' => "deteksi-masuk", 'method' => 'POST', 'type' => 'controller','to' => 'Auth\authController@login_detect',],
    // ['url' => "logout", 'method' => 'POST', 'type' => 'controller','to' => 'Auth\authController@logout',],
    // ['url' => "home",'type' => 'controller','to' => 'Auth\homeController@home',],

    [
        'url'  => '', // Home (main index.php file)
        'type' => 'controller',
        'to'   => 'indexController@view',
    ],   
    
    [
        'url'  => 'uploadImage',
        'type' => 'controller',
        'method' => 'post',
        'to'   => 'indexController@uploadImage',
    ],   
];
