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

require_once "../backend/includes/db.inc.php";

if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || !isset($_SESSION['username'])) {
   header("Location: ../accounts/login");
}

class Router{
    // protected $server_request_uri = null;
    // protected $split_uri = null;
    // protected $get_uri = null;

    public function __construct(){
        $this->server_request_uri = trim($_SERVER['REQUEST_URI'], "/");
        $this->split_uri = explode("?", $this->server_request_uri);
        // print_r($this->split_uri);
        $this->get_uri = explode("/", $this->split_uri[0]);
        $this->get_uri = isset($this->get_uri[1]) ? $this->get_uri[1] : null;
    }

    public function getAction(){
        return $this->get_uri;
    }

    public function route(){

        // var_dump($this->split_uri);
        // echo count(explode("/", $this->split_uri[0]));

        // echo "<br>";
        // var_dump($this->get_uri);
        // echo "<br>";

        // var_dump($_GET);

        if ($this->get_uri == null || $this->get_uri == "/" || $this->get_uri == "") {
            require_once "public/dashboard.php";
        } else {
            if (count(explode("/", $this->split_uri[0])) > 3) {
                require_once "public/404.php";
                echo $this->split_uri[1];
                return false;
            } else {
                switch ($this->get_uri) {
            
                    case 'dashboard':
                        require_once "public/dashboard.php";
                        break;

                    case 'banks':
                        require_once "public/bank_details.php";
                        break;

                    case 'farm-projects':
                        require_once "public/farm_projects.php";
                        break;

                    case 'investments':
                        require_once "public/investments.php";
                        break;

                    case 'transactions':
                        require_once "public/transactions.php";
                        break;

                    case 'open-projects':
                        require_once "public/open_projects.php";
                        break;
                        
                    case 'wallet':
                        require_once "public/wallet.php";
                        break;
                    
                    case 'profile':
                        require_once "public/profile.php";
                        break;

                    case 'action':
                        require_once "public/action.php";
                        break;
            
                    default:
                        require_once "public/404.php";
                        break;
                }
            }
        }

    }
}

$router = new Router();
$router->route();

