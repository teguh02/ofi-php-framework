<?php

namespace vendor\OFI_PHP_Framework;

use App\provider\event;
use vendor\OFI_PHP_Framework\Controller;
use App\Core\helper;
use App\Middleware\kernel as middlewareKernel;

session_start();
global $config;
require 'config.php';

// Dont change this line
define('BASEURL', $_SERVER["DOCUMENT_ROOT"]);

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
                // $controller = new Controller();
                // $controller->error500('Something went wrong, please contact your admin');
                break;
        
            default:
                error_reporting(0);
                $controller = new Controller();
                $controller->error500('Something went wrong, please contact your admin');
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
        $this->whenRun();
        $this->route();
    }

    /**
     * Method searchByValue
     * For search route in array data.
     */
    public function searchByValue($id, $array)
    {
        foreach ($array as $key => $val) {
            // Jika Request URI sama dengan /
            // dan url kosong ditemukan maka akan dialihkan ke index
            if ($this->project_index_path == '/' && $val['url'] == '') {
                $resultSet = $val;

                return $resultSet;
            } else {
                if (strpos($id, $val['url']) !== false) {
                    $resultSet = $val;

                    return $resultSet;
                }
            }
        }

        return null;
    }

    public function route()
    {
        include 'route/web.php';

        $get_url = $this->project_index_path;

        // Konvert string ke array
        $url_array = str_split($get_url);

        // Proses menghilangkan data array index 0
        // dan data array index 0 adalah tanda /

        array_shift($url_array);

        // Hasil URL setelah melalui proses
        // hapus tanda / di awal URL

        $url = implode('', $url_array);

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
                    $className = '\\App\\Controllers\\'.$get_only_Controller_Name;
                    $classNameController = new $className();
                    $classNameController->$get_only_Method_Name();
                } else {
                    $controller->error500('Error '.$searchValue['url'].' url is '.strtoupper($searchValue['method']).' HTTP Method');
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
                $classNameController -> $method_name();
            } else {
                $controller->error404();
            }
        }
    }
}
