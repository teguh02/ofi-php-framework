<?php

namespace App\Middleware\auth;

use App\users;
use vendor\OFI_PHP_Framework\Controller;

/**
 * Login Middleware
 * you can use this middleware to your controller
 * if you want to prohibit not login user for accessing
 * your controller.
 */
class auth extends Controller
{
    public function check()
    {
        $app_id_user = $this->getSession('app_id_user');
        $users = users::where('id', $app_id_user) -> first();

        if(!$users) {
            return $this->message()->js()->error('You must login to access this sites', '/');
        }
    }
}
