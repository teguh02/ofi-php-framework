<?php

namespace App\Controllers\Auth;

use vendor\OFI_PHP_Framework\Controller;
use App\Middleware\auth\auth;

class authController extends Controller
{

    // Redirect to login page
    public function login()
    {
        if ($_SESSION['login_user_status'] == 'sukses' && $_SESSION['id_user'] != '') {
            $this->loadTemplate('Auth\home', null);
        } else {
            $this->loadTemplate('Auth\login', null);
        }
    }

    // Redirect to register page
    public function register()
    {
        if ($_SESSION['login_user_status'] == 'sukses' && $_SESSION['id_user'] != '') {
            $this->loadTemplate('Auth\home', null);
        } else {
            $this->loadTemplate('Auth\register', null);
        }
    }

    public function saveRegister()
    {
        if($this->must_post()) {
            $this->whenRegistration();
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();

            // Code here
        }
    }

    public function deteksiLogin()
    {
        if($this->must_post()) {
            $this->whenLogin();
            
            // Your Code
        }
    }

    public function logout()
    {
        if($this->must_post()) {
            $this->whenLogout();
            
            // Code

            $flash = new \Plasticbrain\FlashMessages\FlashMessages();
            $flash->success('Successfuly logout', '/login');
        }
    }
}
