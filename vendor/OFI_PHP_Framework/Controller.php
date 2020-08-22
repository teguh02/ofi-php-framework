<?php

namespace vendor\OFI_PHP_Framework;

use App\provider\event;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use DebugBar\StandardDebugBar;

class Controller extends event
{
    protected $data = [];
    private $response = null;
    private $request = null;
    private $header_request = false;

    public function __construct()
    {
        global $config;
        $capsule = new DB;
        $capsule->addConnection([
            'driver'    => $config['driver'] != null ? $config['driver'] : 'mysql' ,
            'host'      => $config['localhost'] != null ? $config['localhost'] : 'localhost',
            'port'      => $config['port'] != null ? $config['port'] : '3306',
            'database'  => $config['database'] != null ? $config['database'] : 'ofi',
            'username'  => $config['username'] != null ? $config['username'] : 'root',
            'password'  => $config['password'] != null ? $config['password'] : '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }

    /**
     * Untuk memvalidasi bahwa untuk mengunjungi suatu method 
     * yang diberi kode ini harus menggunakan http method post
     */

    public function must_post()
    {
        if ($_SERVER['REQUEST_METHOD'] != "POST") {
            $this->response() -> error500("Error, because this method are using POST method");
        } else {
            return true;
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
        if($this->response) {
            if(strpos($url, 'http') !== false || strpos($url, 'https') !== false) {
                return header('Location: '.$url);
            } else {
                return header('Location: '. PROJECTURL . '/' . $url);
            }        
        } else {
            echo "Wrong method please select response chain method";
        }
    }

    public function error404()
    {
        $this->whenError();
        $viewData['status'] = 404;
        $viewData['title'] = 'Not Found';
        $viewData['msg'] = 'Sorry but the page you are looking for does not exist, have been removed.';

        $this->pageError($viewData);
    }

    public function error500($pesan)
    {
        $this->whenError();
        $viewData['status'] = 500;
        $viewData['title'] = 'Server Error';
        $viewData['msg'] = $pesan;

        $this->pageError($viewData);
    }

    public function loadView($viewName, $viewData = [])
    {
        $this->loadTemplate($viewName, $viewData);
    }

    public function loadTemplate($viewName, $viewData = [])
    {
        extract($viewData);

        if(ENVIRONMENT == 'development') {
            $debugbar = new StandardDebugBar();
            $debugbarRenderer = $debugbar->getJavascriptRenderer();	
            $debugbar["messages"]->addMessage("OFI PHP Framework Ready To Use!");
            $debugbar["messages"]->addMessage("Base DIR Project : " . BASEURL);
        }

        echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>' . PROJECTNAME . '</title>
                <meta charset="utf-8">
                <meta name="description" content="'. DESCRIPTION .'" >
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta property="og:title" content="'. PROJECTNAME .'" />
                <meta property="og:type" content="website" />
                <meta property="og:url" content="'. PROJECTURL .'" />
                <link rel="shortcut icon" href="'. PROJECTURL .'/assets/favicon.png">
                <meta property="og:image" content="'. PROJECTURL .'/assets/favicon.png" />
                <meta name="robots" content="index, follow">
                <meta name="keywords" content="'. KEYWORDS .'">
                <meta name="author" content="'. AUTHOR .'">
                <meta name="google-site-verification" content="'. GoogleSiteVerification .'" />

                    <link rel="stylesheet" type="text/css" href="'. PROJECTURL .'/assets/css/bootstrap.min.css">
                    <script src="'. PROJECTURL .'/assets/js/jquery.min.js"></script>
                    <script src="'. PROJECTURL .'/assets/js/bootstrap.min.js"></script>';
                    if(ENVIRONMENT == "development") echo $debugbarRenderer->renderHead();
            echo '
            </head>
            <body>';

                $this->loadViewInTemplate($viewName,$viewData);
                if(ENVIRONMENT == "development") echo $debugbarRenderer->render();

            echo '
            </body>
            </html>
            ';
    }

    public function loadViewInTemplate($viewName, $viewData)
    {
        $flash = new \Plasticbrain\FlashMessages\FlashMessages();
        $helper = new \App\Core\helper();
        extract($viewData);
        include 'Views/'.$viewName.'.ofi.php';
    }

    private function pageError($viewData)
    {
        extract($viewData);

        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title> '. $status .' Error - '. PROJECTNAME .'</title>
                <link rel="stylesheet" type="text/css" href="'. PROJECTURL .'/assets/css/bootstrap.min.css">
                <style>body {margin: 0px !important;}html, body {overflow: hidden !important;}</style>
            </head>

            <body>
                <div class="container-fluid text-center d-flex justify-content-center align-items-center" style="height: 100vh;">

                    <div>
                        <h1 class="display-2"> '. $status .' ' . $title . '</h1>
                        <p>
                            '. $msg .'
                        </p>

                        <br>

                        <a onclick="window.history.back();" href="#">
                            <button class="btn btn-light border w-50">Go Back</button>
                        </a>
                    </div>

                </div>
            </body>
            </html>
        ';
    }
}
