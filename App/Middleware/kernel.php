<?php

namespace App\Middleware;

use ofi\ofi_php_framework\Helper\helper;
use ofi\ofi_php_framework\Middleware\removeTrailingSlash;
use App\Middleware\cors;

class kernel {

    /**
     * You can register all middleware 
     * here for your system
     */

    public function register()
    {
        removeTrailingSlash::handle();
        cors::allow();

        // Your middleware here
    }
}