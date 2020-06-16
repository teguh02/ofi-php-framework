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

    public function deteksiLogin()
    {
        if($this->must_post()) {
            $this->whenLogin();
            $db = new DB();
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();
            $email_or_username = $this->request() -> input('emailorusername');
            $password = $this->request() -> input('password');

            $cek_login = $db->sql("SELECT * FROM users WHERE email = '$email_or_username' OR username = '$email_or_username'") 
                            -> run();
            if(count($cek_login[0]) > 0 && password_verify($password, $cek_login[0]['password'])) {
                $_SESSION['login_user_status'] = 'sukses';
                $_SESSION['id_user'] = $cek_login[0]['id'];

                $this->loadTemplate('Auth/home');
            } else {
                $flash->error('Failed to login, please try again', '/login');
            }
        }
    }

    public function logout()
    {
        if($this->must_post()) {
            $this->whenLogout();
            $_SESSION['login_user_status'] = null;
            $_SESSION['id_user'] = null;
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();
            $flash->success('Successfuly logout', '/login');
        }
    }
}
