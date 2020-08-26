<?php 

namespace vendor\OFI_PHP_Framework\Support;

/**
 * Trait Session
 */

trait Session {
    
    /**
     * Untuk menyetel session tertentu pada SESSION PHP
     */

    public function setSession($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Untuk mendapatkan semua data atau 
     * salah satu saja data SESSION
     */

    public function getSession($params = false)
    {
        if($params != false) {
            if(isset($_SESSION[$params])) {
                return $_SESSION[$params];
            } else {
                return 0;
            }
        } else {
            return $_SESSION;
        }
    }
}