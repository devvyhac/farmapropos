<?php
session_start();

// require_once "../backend/includes/db.inc.php";
// $no_access = ["csss", "fonts", "images", "includes", "js", "public", "scss"];
// $server_request_uri = trim($_SERVER['REQUEST_URI'], "/");
// $split_uri = explode("?", $server_request_uri, 2);
// $get_uri = $split_uri[0];
// $get_uri = explode("/", $get_uri);
// $get_uri = isset($get_uri[2]) ? $get_uri[2] : null;

// if (in_array($get_uri, $no_access)) {
//     echo $get_uri;
// }

class Router{
    protected $server_request_uri = null;
    protected $split_uri = null;
    protected $get_uri = null;

    public function __construct(){
        $this->server_request_uri = trim($_SERVER['REQUEST_URI'], "/");
        $this->split_uri = explode("?", $this->server_request_uri, 2);
        $this->get_uri = $this->split_uri[0];
        $this->get_uri = explode("/", $this->get_uri);
        $this->get_uri = isset($this->get_uri[2]) ? $this->get_uri[2] : null;
    }

    public function getAction(){
        return $this->get_uri;
    }

    public function route(){

        // $_GET['page'] = "home";
        // $_GET['id'] = 1;

        // var_dump($this->split_uri);
        // echo count(explode("/", $this->split_uri[0]));

        // echo "<br>";
        // var_dump($this->get_uri);
        // echo "<br>";

        // var_dump($_GET);

        $no_access = ["css", "fonts", "images", "includes", "js", "public", "scss"];

        if ($this->get_uri == null || $this->get_uri == "/" || $this->get_uri == "") {
            require_once "public/login.php";
        } else {
            if (count(explode("/", $this->split_uri[0])) > 3) {
                require_once "public/404.php";
                echo $this->split_uri[0];
                return false;
            } else {
                if (!in_array($this->get_uri, $no_access)) {
                    
                    switch ($this->get_uri) {
            
                        case 'dashboard':
                            require_once "public/admin.php";
                            break;
    
                        case 'tables':
                            require_once "public/tables.php";
                            break;
    
                        case 'login':
                            require_once "public/login.php";
                            break;
    
                        case 'action':
                            require_once "public/action.php";
                            break;
            
                        default:
                            require_once "public/404.php";
                            break;
                    }
                } else {
                    header("Location: ..");
                }
            }
        }

    }
}

$router = new Router();
$router->route();

