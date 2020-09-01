<?php

namespace vendor\OFI_PHP_Framework;

use App\provider\event;
use vendor\OFI_PHP_Framework\Controller;
use Exception;
use App\Middleware\kernel as middlewareKernel;
use vendor\OFI_PHP_Framework\Controller\Route;

session_start();
global $config;
require 'config.php';

// Dont change this line
define('BASEURL', $_SERVER["DOCUMENT_ROOT"]);


/**
 * Add CSRF input hidden to your form
 */

define('CSRF', \Volnix\CSRF\CSRF::getHiddenInputString());

class Core extends event
{
    public function __construct()
    {
        $middleware = new middlewareKernel();
        $middleware->register();

        $this->project_index_path = $_SERVER['REQUEST_URI'];

        switch (ENVIRONMENT) {
            case 'development':
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
        
                $whoops = new \Whoops\Run();
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
                $whoops->register();
                error_reporting(E_ALL);
                error_reporting(E_ALL & E_NOTICE & E_DEPRECATED & E_STRICT & E_USER_NOTICE & E_USER_DEPRECATED);
                break;
        
            case 'production':
                
                error_reporting(0);
                try {
                    $this->run();
                } catch (\Throwable $th) {
                    $controller = new Controller();
                    $controller->error500('Something went wrong, please contact this sites admin');
                    die();
                }

                break;

            default:
                error_reporting(0);
                $controller = new Controller();
                $controller->error500('Something went wrong, please set your application environment');
                break;
        }
    }

    /**
     * Method Run
     * This method will be run for the
     * first time while the program is running.
     */
    public function run()
    {
        // To block PUT, PATCH, DELETE Request (Only GET and POST are allowed in our system)   
        $request_method = $_SERVER['REQUEST_METHOD'];
        if($request_method == 'PUT' || $request_method == 'PATCH' || $request_method == 'DELETE') {
            throw new Exception('Sorry ' . $request_method . " Method not allowed in our system! You must use GET or POST", 1);
            die();
        }

        $this->whenRun();
        $this->route();
        $this->CSRF();
    }

    /**
     * To activate and validate CSRF Protection
     * in our system
     * Only POST will get validate by our system
     */

    public function CSRF()
    {
        // Detect HTTP Method
        $request_method = $_SERVER['REQUEST_METHOD'];

        // Get Request URL
        $request_url = $_SERVER['REQUEST_URI'];

        if ($request_method == "POST") {

            // generic POST data
            $getToken = null;
            if (isset($_POST)) {
                $getToken = $_POST;
            } else {
                $getToken = $_REQUEST;
            }

            // Cari apakah url pada request_url ada dalam daftar bypassCSRF?
            include 'App/Middleware/bypassCSRF.php';

            // Jika tidak ditemukan dalam daftar array maka tampilkan pesan
            if(!in_array($request_url, $bypass)) {
                if (!\Volnix\CSRF\CSRF::validate($getToken) ) {
                    throw new Exception("Invalid CSRF token! Please add csrf token to your code", 1);
                    die();
                }
            }
        }
    }

    /**
     * Method searchByValue
     * For search route in array data.
     */
    public function searchByValue($id, $array)
    {

        for ($i=0; $i < count($array) ; $i++) { 
            // Jika Request URI sama dengan /
            // dan url kosong ditemukan maka akan dialihkan ke index
            if ($this->project_index_path == '/' && $array[$i]['url'] == '') {
                return $array[$i]['url'];
            } else {

                if (strtolower($id) == strtolower($array[$i]['url'])) {
                    return $array[$i]['url'];
                }             
            }

            return false;
        }
    }

    public function route()
    {
        include 'route/web.php';
        $route = Route::getAsArray();
        $get_url = $this->project_index_path;

        /**
         * Konvert string ke array
         * Hasilnya str_split($get_url) akan menjadi
         * Array
         * (
         *     [0] => /
         *     [1] => t
         *     [2] => e
         *     [3] => s
         *     [4] => t
         * )
         */

        $url_array = str_split($get_url);

        /**
         * Proses menghilangkan menggunakan array_shift($url_array), 
         * pada data array index 0 (tanda '/' hilang)
         * Hasil akhirnya menjadi
         * Array
         * (
         *     [0] => t
         *     [1] => e
         *     [2] => s
         *     [3] => t
         * )
         */

        array_shift($url_array);

        /**
         * Hasil URL setelah melalui proses diatas akan menjadi 
         * dari seperti ini 
         * Array
         * (
         *     [0] => t
         *     [1] => e
         *     [2] => s
         *     [3] => t
         * )
         * 
         * akan menjadi seperti ini
         * test
         * dengan ada nya proses $url = implode('', $url_array);
         */

        //  Proses mendeteksi apakah ada parameter atau tidak. 
        // Jika ada maka bersihkan semua parameternya dan ambil url utamanya saja

         if(substr(strtok(implode('', $url_array), '?'), -1) == '/') {
             $url = rtrim(strtok(implode('', $url_array), '?'), '/');
         } else {
             $url = strtok(implode('', $url_array), '?');
         }

        $searchValue = $this->searchByValue($url, $route);
        $controller = new Controller();

        // Jika URL Tersedia

        if ($searchValue) {

            if ($searchValue['type'] == 'view') {

                // Checking HTTP Method
                if (!$searchValue['method'] || $_SERVER['REQUEST_METHOD'] === strtoupper($searchValue['method'])) {
                    $controller->loadView($searchValue['to'], $paramData);
                } else {
                    $controller->error500('Error '.$searchValue['url'].' url is '.strtoupper($searchValue['method']).' HTTP Method');
                }

                // Jika typenya belum terdeklarasi
            } elseif (!$searchValue['type'] || $searchValue['type'] == '') {
                $controller->error500("Route type can't Be null, please declare the url type on your route files");
            } elseif ($searchValue['type'] == 'controller') {

                // Jika type controller maka memanggil controller
                // @ untuk memanggil methodnya

                $request_controller = explode('@', $searchValue['to']);

                $get_only_Controller_Name = $request_controller[0];
                $get_only_Method_Name = $request_controller[1];

                // Checking HTTP Method
                if (!$searchValue['method'] || $_SERVER['REQUEST_METHOD'] === strtoupper($searchValue['method'])) {

                    // Checking Middleware (if isset)
                    if(isset($searchValue['middleware']) && $searchValue['middleware'] != null) {
                        $explodeMiddleware = explode('@', $searchValue['middleware']);

                        $middlewareFunction = (String) $explodeMiddleware[1];

                        $middleware = '\\App\\Middleware\\' . (String) $explodeMiddleware[0];
                        $middlewareClass = new $middleware;
                        $middlewareClass -> $middlewareFunction();
                    }

                    $className = '\\App\\Controllers\\'.$get_only_Controller_Name;
                    $classNameController = new $className();

                    if (!method_exists($classNameController, $get_only_Method_Name)) {
                        $classNameErr = explode('\\', $className);
                        throw new Exception('File ' . str_replace('\\', '/', $className) .  ".php Error : Can't find method " . $get_only_Method_Name . '() in Class ' . $classNameErr[count($classNameErr) - 1], 1);
                        die();
                    }

                    $classNameController->$get_only_Method_Name();
                } else {
                    $controller->error500('Error! '.$searchValue['url'].' is '.strtoupper($searchValue['method']).' HTTP Method');
                }
            } 
        } else {
            // Jika URL tidak tersedia maka cek pada folder controller

            $url_explode = explode('/', $url);
            $total_url_explode = count($url_explode);

            // Jika url memuat tanda ? (untuk parameter)
            // maka hapus tanda tersebut dan seterusnya kebelakang
            for ($i=0; $i < $total_url_explode ; $i++) { 
                if (stripos($url_explode[$i], "?") !== false) {
                    $explode_lagi = explode('?', $url_explode[$i]);
                    array_pop($explode_lagi);
                    $url_explode[$total_url_explode - 1] = $explode_lagi[0];
                }
            }

            $files = 'App/Controllers';
            $class_name = '\\App\\Controllers';

            for ($i=0; $i < $total_url_explode - 1 ; $i++) { 
                $files .= '/' . $url_explode[$i];
            }

            for ($i=0; $i < $total_url_explode - 1 ; $i++) { 
                $class_name .= "\\" . $url_explode[$i];
            }

            $files .= 'Controller.php';
            $class_name .= 'Controller';

            if(file_exists($files)) {
                $method_name = $url_explode[$total_url_explode - 1];
                $className = $class_name;
                $classNameController = new $className();

                if (!method_exists($classNameController, $method_name)) {
                    
                    if($method_name == '' || $method_name == null) {
                        throw new Exception("Method can't null in your request", 1);
                        die();
                    }

                    $classNameErr = explode('\\', $className);
                    throw new Exception('File ' . str_replace('\\', '/', $className) .  ".php Error : Can't find method " . $method_name . '() in Class ' . $classNameErr[count($classNameErr) - 1], 1);
                    die();
                }

                $classNameController -> $method_name();
            } else {
                throw new Exception("Class " . $class_name . ' not found, or some route are disabled', 1);
            }
        }
    }
}
