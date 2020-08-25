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
        if($this->must_post()) {            
            // Receive data from register page
            $data = [
                'fullname'    => $this->request()->input('fullname'),
                'username'    => $this->request()->input('username'),
                'email'       => $this->request()->input('email'),
                'password'    => password_hash($this->request()->input('password'), PASSWORD_BCRYPT),
            ];

            // Save data to database
            $this->saveRegisterData($data);
        }
    }
}
