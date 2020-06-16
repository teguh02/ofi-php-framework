<?php

namespace App\Controllers\Auth;

use vendor\Controller;
use App\Middleware\auth\auth;

class homeController extends Controller
{
    public function __construct()
    {
        $auth = new auth();
        $auth -> check();
    }

    public function home()
    {
        $this->loadTemplate('Auth/home', null);
    }
}
