<?php

namespace App\Middleware;

use ofi\ofi_php_framework\Helper\helper;
use App\Middleware\cors;

class kernel {

    /**
     * You can register all middleware 
     * here for your system
     */

    public function register()
    {
        helper::blockIp();
        cors::allow();

        // Your middleware here
    }
}