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

require_once "backend/includes/db.inc.php";

class Router{
    // protected $server_request_uri = null;
    // protected $split_uri = null;
    // protected $get_uri = null;

    public function __construct(){
        $this->server_request_uri = trim($_SERVER['REQUEST_URI'], "/");
        $this->split_uri = explode("?", $this->server_request_uri);
        // print_r($this->split_uri);
        $this->get_uri = $this->split_uri;
        // print_r($this->get_uri);
        $this->get_uri = isset($this->get_uri[0]) ? $this->get_uri[0] : null;
    }

    public function getAction(){
        return $this->get_uri;
    }

    public function route(){

        // $_GET['page'] = "home";
        // $_GET['id'] = 1;
        
        // echo $this->server_request_uri;
        // echo "<br>";
        
        // for($i=0; $i<count($this->split_uri); $i++){
        //     echo $this->split_uri[$i];
        //     echo "<br>";
        // }

        // var_dump($split_uri);
        // echo count(explode("/", $split_uri[0]));

        // echo "<br>";
        // var_dump($this->get_uri);

        // var_dump($_GET);

        if ($this->get_uri == null || $this->get_uri == "/" || $this->get_uri == "") {
            $this->get_uri = "home";
            require_once "views/home.php";
        } else {
            $url_length = count(explode("/", $this->split_uri[0]));
            if ($url_length > 1) {
                require_once "views/error.php";
                // var_dump($this->split_uri);
            } else {
                switch ($this->get_uri) {
                    case 'home':
                        require_once "views/home.php";
                        break;
            
                    case 'about':
                        require_once "views/about.php";
                        break;
            
                    case 'contact':
                        require_once "views/contact.php";
                        break;
            
                    case 'faq':
                        require_once "views/faq.php";
                        break;
            
                    case 'farms':
                        require_once "views/farms.php";
                        break;
            
                    case 'projects':
                        require_once "views/projects.php";
                        break;

                    case 'checkout':
                        require_once "views/checkout.php";
                        break;

                    case 'pay':
                        require_once "views/pay.php";
                        break;
                        
                    case 'update-wallet':
                        require_once "views/update-wallet.php";
                        break;

                    case 'payment-verification':
                        require_once "views/payment-verification.php";
                        break;
            
                    case 'signup':
                        header("Location: accounts/signup");
                        break;
            
                    case 'login':
                        header("Location: accounts/login");
                        break;

                    case 'dashboard':
                        header("Location: account/dashboard");
                        break;
                        

                    case 'profile':
                        header("Location: account/profile");
                        break;

                    case 'admin':
                        header("Location: admin");
                        break;
            
                    default:
                        require_once "views/error.php";
                        break;
                }
            }
        }

    }
}

$router = new Router();
$router->route();
