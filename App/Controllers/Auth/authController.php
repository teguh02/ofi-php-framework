<?php

namespace App\Controllers\Auth;

use vendor\Controller;
use App\Core\helper as h;
use App\Middleware\auth\auth;
use vendor\DB;
use App\provider\event;

class authController extends Controller
{

    // Redirect to login page
    public function login()
    {
        $this->loadTemplate('Auth\login', null);
    }

    // Redirect to register page
    public function register()
    {
        $this->loadTemplate('Auth\register', null);
    }

    public function saveRegister()
    {
        if($this->must_post()) {
            $this->whenRegistration();
            $db = new DB();
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();

            $cek_email = $db->select('email') -> from('users') -> where('email', $this->request() -> input('email')) -> get();
            $cek_username = $db->select('username') -> from('users') -> where('username', $this->request() -> input('username')) -> get();

            if(count($cek_email) > 0 && count($cek_username) > 0) {
                $flash->error('Email or username already exists, please try again', '/register');
            } else {
                $status = $db -> insert() -> into('users')
                        -> value('fullname', $this->request() -> input('fullname'))
                        -> value('username', $this->request() -> input('username'))
                        -> value('email', $this->request() -> input('email'))
                        -> value('password', h::hash($this->request() -> input('email')))
                        -> save();
                
                if ($status) {
                    $flash->success('Registration success, please login now', '/register');
                } else {
                    $flash->error('Failed to registration, try again', '/register');
                }
            }
        }
    }

    public function login_detect()
    {
        $this->whenLogin();
        
        $cek = $Databse_engine->deteksi_login($Auth);

        if ($cek['status'] == 'yes') {
            $_SESSION['login_user'] = 'sukses';
            $_SESSION['id_user'] = $cek['id'];
            helper::redirect('/home');
        } else {
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();
            $flash->error('Failed to login, try again', '/login');
        }
    }

    public function logout()
    {
        $this->whenLogout();
        $_SESSION['login_user'] = null;
        $_SESSION['id_user'] = null;
        helper::redirect('/login');
    }
}
