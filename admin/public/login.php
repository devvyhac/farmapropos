<?php include_once "../backend/includes/db.inc.php";

if (isset($_SESSION['admin_id']) || isset($_SESSION['email']) || isset($_SESSION['username'])) {
    header("Location: dashboard");
}

function sanitize($data){
    $data = filter_var($data, FILTER_SANITIZE_STRING);
    $data = explode(";", $data);
    $data = explode("&", $data[0]);
    $data = explode("?", $data[0]);
    $data = $data[0];
    return $data;
}

class LOGIN extends DATABASE{
    private $error = [];

    public function __construct() {
        parent::__construct();
    }

    public function getError(){
        return $this->error;
    }

    public function session_init($data) {
        session_start();
        $_SESSION['admin_id'] = $data->user_id;
        $_SESSION['email'] = $data->email;
        $_SESSION['username'] = $data->username;
        $_SESSION['display_name'] = $data->display_name;
        $_SESSION['user_image'] = $data->user_image;
    }

    public function login ($data) {
        $this->error['error_type'] = null;
        $this->error['login_error'] = null;

        if (!isset($data['admin-username']) || !isset($data['admin-password'])) {
            $this->error['error_type'] = "Empty Fields";
            $this->error['login_error'] = "Please Fill All Fields";
        } else {
            
            $username = sanitize($data['admin-username']);
            $password = md5(sanitize($data['admin-password']));
        
            $sql = "SELECT * FROM super_user WHERE username = :username AND password = :password";
        
            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch();
                $this->session_init($result);
                header("Location: dashboard");
            } else {
                $this->error['error_type'] = "Access Denied";
                $this->error['login_error'] = "Invalid Login Credentials";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $login = new LOGIN();
    $login->login($_POST);
    $error = $login->getError();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">	
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <title>Document</title>
</head>
<body id="admin-login">

    <a href=".." class="btn btn-danger m-3"><i class="fa fa-arrow-left"></i> Home</a>
    <div class="container h-100 py-1">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6 col-md-9 col-sm-12 col-xs-12">
                <div class="card" id="card">
                    <div class="card-header bg-dark text-light py-3">
                        <h3 class="m-0"><i class="fa fa-user-shield mr-2"></i> <span>Admin Panel</span></h3>
                    </div>
                    <div class="card-body py-5">
                        
                        <div <?php echo (isset($error['error_type']) ? "id=error_msg" : ""); ?> class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong class="mr-2">
                                <span><?php echo (isset($error['error_type']) ? $error['error_type'] : ""); ?></span>
                                <i class="fa fa-exclamation-triangle"></i>
                            </strong>
                                <span><?php echo (isset($error['login_error']) ? $error['login_error'] : ""); ?></span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="login" method="POST" autocomplete="off">
                            <div class="input-group input-group-lg mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-lg">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" value="<?php echo (isset($_POST['admin-username']) ? sanitize($_POST['admin-username']) : "") ?>" name="admin-username" class="form-control" placeholder="Username" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                            </div>

                            <div class="input-group input-group-lg my-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-lg">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input type="password" name="admin-password" class="form-control" placeholder="Password" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                            </div>
                            <div class="form-submit">
                                <button class="btn btn-success" name="submit">Access <i class="fa fa-user-shield"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>