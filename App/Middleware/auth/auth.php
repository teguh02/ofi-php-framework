<?php

namespace App\Middleware\auth;

use vendor\Controller;
use vendor\DB;
use App\Core\helper;

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
        if ($_SESSION['login_user_status'] == 'sukses' && $_SESSION['id_user'] != '') {
            return true;
        } else {
            $this->response() -> redirect('/Auth/auth/login');
        }
    }
}
