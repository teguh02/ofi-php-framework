<?php

namespace App\Controllers\Auth;

use vendor\OFI_PHP_Framework\Controller;
use App\Middleware\auth\auth;

class homeController extends Controller
{
    public function __construct()
    {
        // Middleware Auth
        $auth = new auth();
        $auth -> check();
    }

    public function home()
    {
        $this->loadTemplate('Auth/home', null);
    }
}
