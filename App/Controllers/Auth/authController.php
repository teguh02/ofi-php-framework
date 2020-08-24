<?php

namespace App\Controllers\Auth;

use vendor\OFI_PHP_Framework\Controller;
use App\users;
use App\provider\event;
use vendor\OFI_PHP_Framework\Controller\auth;

class authController extends Controller
{
    use auth;

    public function saveRegister()
    {
        $event = new event();
        $event->whenRegistration();

        if($this->must_post()) {
            $this->whenRegistration();
            
            $fullname = $this->request()->input('fullname');
            $username = $this->request()->input('username');
            $email = $this->request()->input('email');
            $password = password_hash($this->request()->input('password'), PASSWORD_BCRYPT);

            $cek = users::where('email', $email) -> orWhere('username', $username) -> first();

            if($cek) {
                $this->message()->flash()->error('Username or email are registered in our system', '/register');
                die();
            }

            $users = new users();
            $users->fullname = $fullname;
            $users->username = $username;
            $users->email = $email;
            $users->password = $password;
            
            if ($users->save()) {
                $this->message()->js()->success('Successfuly save your account', '/register');
            } else {
                $this->message()->js()->error('Failed to save your account', '/register');
            }
        }
    }
}
