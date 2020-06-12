<?php

namespace vendor;

use App\Designs\design;
use vendor\DB;
use App\provider\event;

class Controller extends event
{
    protected $data = [];
    private $response = null;
    private $request = null;
    private $header_request = false;

    public function __construct()
    {
        $this->flash = new \Plasticbrain\FlashMessages\FlashMessages();
    }

    /**
     * Untuk memvalidasi bahwa untuk mengunjungi suatu method 
     * yang diberi kode ini harus menggunakan http method post
     */

    public function must_post()
    {
        if ($_SERVER['REQUEST_METHOD'] != "POST") {
            $this->response() -> error500("Error, because this method are using POST method");
        } 
    }

    /**
     * Chaining method request
     */

    public function request()
    {   
        $this->request = true;
        return $this;
    }

    public function all()
    {
        if($this->request) {
            // Jika header_request true maka tampilkan semua data heder
            if($this->header_request) {
                return getallheaders();
            } else {

                // Namun jika header_request false maka tampilkan 
                // semua data raw yang masuk

                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $json = file_get_contents('php://input');
                    return json_decode($json, true);
                } else {
                    echo 'Wrong HTTP Method, you must use POST method';
                }
            }
        } else {
            echo "Wrong method please select request chain method";
        }
    }

    public function input($val_request)
    {
        if($this->request) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $_REQUEST[$val_request];     
            } else {
                return $_GET[$val_request];
            }
        } else {
            echo "Wrong method please select request chain method";
        }
    }

    public function header()
    {
        $this->header_request = true;
        return $this;
    }

    public function first($request_val)
    {
        if($this->request) {
            $header = getallheaders();
            return $header[$request_val];
        } else {
            echo "Wrong method please select request chain method";
        }
    }

    /**
     * Chaining method response
     */

    public function response()
    {
        $this->response = true;
        return $this;
    }

    public function print_r($params)
    {

        if($this->response) {
            echo "<pre>";
                print_r($params);
            echo "<pre>";
        } else {
            echo "Wrong method please select response chain method";
        }
    }

    public function var_dump($params)
    {
        if($this->response) {
            echo "<pre>";
                var_dump($params);
                die();
            echo "<pre>";
        } else {
            echo "Wrong method please select response chain method";
        }
    }

    // Response print to json

    public function json($data, $code)
    {
        if($this->response) {
            // Set content type menjadi application/json
            header('Content-Type: application/json');
            http_response_code($code);
            echo json_encode($data);
        } else {
            echo "Wrong method please select response chain method";
        }
    }

    /**
     * Method redirect
     * To give a new response redirect to a url.
     */

    public function redirect($url)
    {
        if(strpos($url, 'http') !== false || strpos($url, 'https') !== false) {
            return header('Location: '.$url);
        } else {
            return header('Location: '. PROJECTURL . '/' . $url);
        }        
    }

    public function error404()
    {
        $this->whenError();
        $viewData['status'] = 404;
        $viewData['title'] = 'Not Found';
        $viewData['msg'] = 'Sorry but the page you are looking for does not exist, have been removed.';

        extract($viewData);
        include 'vendor/error_404.php';
    }

    public function error500($pesan)
    {
        $this->whenError();
        $viewData['status'] = 500;
        $viewData['title'] = 'Server Error';
        $viewData['msg'] = $pesan;

        extract($viewData);
        include 'vendor/error_404.php';
    }

    public function loadView($viewName, $viewData = [])
    {
        $this->loadTemplate($viewName, $viewData);
    }

    public function loadTemplate($viewName, $viewData = [])
    {
        extract($viewData);
        include 'vendor/template.php';
    }

    public function loadViewInTemplate($viewName, $viewData)
    {
        $flash = new \Plasticbrain\FlashMessages\FlashMessages();
        $helper = new \App\Core\helper();
        extract($viewData);
        include 'Views/'.$viewName.'.ofi.php';
    }
}
