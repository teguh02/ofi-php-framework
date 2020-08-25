<?php 

namespace vendor\OFI_PHP_Framework\Controller;
use Exception;
use vendor\OFI_PHP_Framework\Controller\getRoute;

class Route {

    protected static $routeStatus;
    protected static $arrayRoute = [];
    private static $_instance = null;
    protected static $index = 0;

    use getRoute;

    public function __construct()
    {
        $this->routeStatus = false;
    }

    public static function start()
    {
        if(self::$_instance == null) {
            self::$_instance = new self();
        } 

        self::$routeStatus = true;  
        return self::$_instance;
    }

    public function route($index)
    {
        if(self::$routeStatus) {
            self::$index = $index;
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function type($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['type'] = $value; 
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function url($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['url'] = str_replace('/', '', $value);
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function to($value)
    {
        if (self::$routeStatus) {

            if(self::$arrayRoute[self::$index]['type'] == 'controller') {
                if (strpos($value, "@") != true) {
                    throw new Exception("Route : ". $value ." error! You must define a method in your route" . '. For example ' . $value . '@example', 1);
                }
            }

            self::$arrayRoute[self::$index]['to'] = $value;
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function method($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['method'] = strtoupper($value);
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function end()
    {
        if (self::$routeStatus) {
            return self::$arrayRoute;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }
}