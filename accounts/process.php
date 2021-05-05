<?php
require_once "../backend/includes/db.inc.php";

// THE PROCESS CLASS
class Process extends DATABASE{

    private $error = []; // THIS IS THE ARRAY FOR ALL ERROR MESSAGES
    protected $confirm = 0; // THIS IS THE CONDITION FOR ACCESSING THE DATABASE
    protected $data = []; // THIS IS THE ARRAY HOLDING ALL POST DATA

    // GETTING THE ERROR MESSAGES FOR BOTH SIGNUP AND LOGIN
    public function get_error(){
        return $this->error;
    }

    // THE CONSTRUCTOR METHOD FOR THE PROCESS CLASS
    public function __construct(){        
        parent::__construct();

        $this->error["empty_err"] = null;
        $this->error['wrong_email'] = null;
        $this->error['name_error'] = null;
        $this->error['phone_error'] = null;
        $this->error['user_error'] = null;
        $this->error['password_error'] = null;
        $this->error['login_error'] = null;
        $this->error['address_error'] = null;
    }

    // SANITIZING METHOD
    public function sanitize($input = []){
        $input = filter_var($input, FILTER_SANITIZE_STRING);
        $input = explode(";", $input);
        $input = trim($input[0]);
        $this->confirm = 1;
        return $input;
    }
    
    // GENERATING UNIQUE ID 
    public function gen_uid($type = ""){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Output: video-g6swmAP8X5VG4jCi.mp4
        // "user_zZIUHbR4Ox39XvSVCs0Al"
        if ($type !== ""){
            $uuid = $type."_".substr(str_shuffle($permitted_chars), 0, 21);
            return $uuid;
        } else {
            return substr(str_shuffle($permitted_chars), 0, 21);
        }
    }

    // NAME VALIDATION METHOD
    public function validateName($input){
        $input = trim($input);
        $nameMatch = "/^[a-zA-Z]*$/";
        $input = filter_var($input, FILTER_SANITIZE_STRING);
        if (preg_match($nameMatch, $input) && strlen($input) >= 3) {
            return $input;
        } else {
            $this->error['name_error'] = "Name Must be only Alphabets and at least 5 characters!";
            return false;
        }
    }

    // USERNAME VALIDATION METHOD
    public function validateUsername($input){
        $input = trim($input);
        $usernameMatch = "/^[a-zA-Z0-9]*$/";
        $input = filter_var($input, FILTER_SANITIZE_STRING);
        if (strlen($input) >= 8) {
            return $input;
        } else {
            $this->error['user_error'] = "Username must have at least 5 Alphabets, 1 digit and must be at least 8 characters long!";
            return false;
        }
    }

    // PASSWORD VALIDATION METHOD
    public function validatePass($input){
        $input = trim($input);
        $input = filter_var($input, FILTER_SANITIZE_STRING);
        if (strlen($input) >= 8){
            return $input;
        } else {
            $this->error['password_error'] = "Password must have at least 1 uppercase alphabet, 1 lowercase alphabet, 1 digit! and must be at least 8 characters long";
            return false;
        }
    }

    // GETTING CONDITION FOR SUCCESSFUL VALIDATION AND SANITIZATION BEFORE LOGIN OF SIGNUP
    public function confirm(){
        return $this->confirm;
    }

    // INITIALIZING SESSION VARIABLES AFTER SIGNUP OR LOGIN
    public function init_session_vars($data){
        $_SESSION['id'] = $data->user_id;
        $_SESSION['email'] = $data->email;
        $_SESSION['username'] = $data->username;
        $_SESSION['dp'] = $data->display_pic;
        $_SESSION['bg_dp'] = $data->bg_pic;
    }

    // VALIDATION AND SANITIZATION OF INPUT FIELDS AGAINST SQL INJECTION ATTACKS
    public function prepare_signup_data($data) {

        // $nameMatch = "/^\S+\w{8,32}\S{1,}/";

        if (!isset($data["first_name"]) || !isset($data["last_name"]) 
            || !isset($data["email"]) || !isset($data["phone"])
            || !isset($data["birthday"]) || !isset($data["gender"])
            || !isset($data["username"]) || !isset($data["password"])){

            $this->error["empty_err"] = "Please Fill all The Fields!";
            $this->error['error_msg'] = "block";

        } else {
            if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)){

                $this->error['wrong_email'] = "Invalid E-Mail Address";

            } else {
                if( $this->validateName($data["first_name"]) && $this->validateName($data["last_name"])){

                    $phoneMatch = "/^[0-9]*$/";

                    if(!preg_match($phoneMatch, $data["phone"]) || strlen($data["phone"]) < 11 ){

                        $this->error['phone_error'] = "Invalid Phone Number format";

                    } else {

                        $this->error['error_msg'] = null;

                        if ($this->validateUsername($data['username'])) {
                            
                            if ($this->validatePass($data['password'])) {
                                foreach ($data as $key => $value) {
                                    $data[$key] = $this->sanitize($data[$key]);
                                }
                                // print_r($data);
                                
                                return $data;
                            }
                        }
                    }
                }
            }
        }
    }

    // METHOD FOR CHECKING FOR DUPLICATE USERNAME OF EMAIL IN THE DATABASE BEFORE SIGNUP
    public function check_email_and_username($email, $username){
        if (!empty($email) && !empty($username)) {

            $sql = "SELECT * FROM users where email = :email OR username = :username";

            $stmt = $this->connect->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            if ($stmt->execute()) {
                if($stmt->rowCount() > 0){
                    $result = $stmt->fetch();
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    // THE ACTUAL SIGNUP METHOD
    public function signup($data, $get=null){
        if($this->data = $this->prepare_signup_data($data)){
            // foreach ($this->data as $key => $value) {
            //     echo $this->data[$key]."<br>";
            // }
            if ($this->confirm === 1) {
                $firstname = $this->sanitize($this->data['first_name']);
                $lastname = $this->sanitize($this->data['last_name']);
                $email = $this->sanitize($this->data['email']);
                $phone = $this->sanitize($this->data['phone']);
                $birthday = $this->sanitize($this->data['birthday']);
                $gender = $this->sanitize($this->data['gender']);
                $username = $this->sanitize($this->data['username']);
                $password = md5($this->sanitize($this->data['password']));
                $uuid = $this->gen_uid("user");
                $vkey = md5($this->gen_uid(""));

                if($this->check_email_and_username($email, $username)){

                    $user_sql = "INSERT INTO users (firstname, lastname, email, phone, dob, gender, username, password, vkey, uuid)
                            VALUES(:firstname, :lastname, :email, :phone, :dob, :gender, :username, :password, :vkey, :uuid);";
                    
                    $address_sql = "INSERT INTO address (user_id) VALUES (LAST_INSERT_ID());";
                    // $wallet_sql = "INSERT INTO wallet (user_id, balance) VALUES (LAST_INSERT_ID(), 0);";
                    

                    $stmt = $this->connect->prepare($user_sql);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':phone', $phone);
                    $stmt->bindParam(':dob', $birthday);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':vkey', $vkey);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':uuid', $uuid);

                    try {
                        if ($stmt->execute()) {
                            if($this->connect->query($address_sql)){
                                $this->login($this->data, $get);
                            } else {
                                $this->error['address_error'] = "Unable to register, due to insert address Error. Please try again!";
                            }
                        }
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                        $this->signup($data, $get=null);
                    }
                }
            }
        }
        
    }

    // THE ACTUAL LOGIN METHOD
    public function login($data, $get=null){
        if (isset($data)) {

            $sql = "";

            if (filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
                $email = $this->sanitize($data['username']);
                // $email = $email;
                $sql = "SELECT * FROM users where email = :email";
                $stmt = $this->connect->prepare($sql);
                $stmt->bindParam(':email', $email);
            } else {
                $username = $this->sanitize($data['username']);
                $sql = "SELECT * FROM users where username = :username";
                $stmt = $this->connect->prepare($sql);
                $stmt->bindParam(':username', $username);
            }

            $password = md5($this->sanitize($data['password']));

            if ($stmt->execute()) {
                if($stmt->rowCount() > 0){
                    $result = $stmt->fetch();
                    if ($password === $result->password) {
                        
                        // INITIALIZING THE SESSION VARIABLES BELOW HERE
                        $this->init_session_vars($result);
                        
                        
                        if (!empty($get)) {
                            header("Location: ../checkout?funding=".$get['funding']."&units=".$get['units']);
                        } else {
                            header("Location: ../account/dashboard");
                        }
                    } else {
                        $this->error['login_error'] = "Incorrect Login Credentials";
                        return false;
                    }
                } else {
                    $this->error['login_error'] = "Incorrect Login Credentials";
                    return false;
                }
            }
        }
    }
}

?>