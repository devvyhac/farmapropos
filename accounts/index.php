<?php

session_start();  
$currentCookieParams = session_get_cookie_params();  
$sidvalue = session_id(); 

setcookie(  
    'PHPSESSID',//name  
    $sidvalue,//value  
    0,//expires at end of session  
    "/; SameSite=None",//path  
    $currentCookieParams['domain'],//domain  
    true //secure  
);

class Router{
    protected $server_request_uri = null;
    protected $split_uri = null;
    protected $get_uri = null;

    public function __construct(){
        $this->server_request_uri = trim($_SERVER['REQUEST_URI'], "/");
        $this->split_uri = explode("?", $this->server_request_uri, 2);
        $this->get_uri = $this->split_uri[0];
        $this->get_uri = explode("/", $this->get_uri);
        $this->get_uri = isset($this->get_uri[1]) ? $this->get_uri[1] : null;
        // var_dump($this->get_uri);
        
    }

    public function getAction(){
        return $this->get_uri;
    }

    public function route(){

        // $_GET['page'] = "home";
        // $_GET['id'] = 1;

        // var_dump($split_uri);
        // echo count(explode("/", $split_uri[0]));

        // echo "<br>";
        // var_dump($this->get_uri);

        // var_dump($_GET);
        
        // echo $this->get_uri;

        if ($this->get_uri == null || $this->get_uri == "/" || $this->get_uri == "") {
            $this->get_uri = "Login";
            require_once "login.php";
        } else {
            if (count(explode("/", $this->split_uri[0])) > 2) {
                require_once "404.php";
                echo $this->split_uri[0];
                return false;
            } else {
                // echo $this->get_uri;
                switch ($this->get_uri) {
            
                    case 'signup':
                        require_once "signup.php";
                        break;
            
                    case 'login':
                        require_once "login.php";
                        break;
            
                    default:
                        require_once "404.php";
                        break;
                }
            }
        }

    }
}

$router = new Router();
$router->route();

