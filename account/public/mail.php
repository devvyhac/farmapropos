<?php 

require_once "../backend/includes/db.inc.php";
session_start();

class MAIL extends DATABASE{
    private $error;
    public function __construct() {
        parent::__construct();
        $this->error = null;
    }

    public function select_item($sql, $param, $data){
        $data = $this->sanitize($data);
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam($param ,$data);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
    
    public function getError(){
        return $this->error;
    }
    
    public function setError($error){
        $this->error = $error;
    }
    
    public function bind_param($param, $data){
        $data = $this->sanitize($data);
        $stmt->bindParam($param ,$data);
    }
    
    public function insert_item($sql, $param, $data){
        $data = $this->sanitize($data);
        $stmt = $this->connect->prepare($sql);
        $stmt->bindParam($param ,$data);
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
    
    public function send_mail () {
        
        $mail_sql = "Select * from users where email = :email";
        $mail = $this->select_item($mail_sql, ":email", $_SESSION['email']);
        // print_r($mail);
        
        if (!empty($mail->vkey) && $email->verified == 0){
            // print_r($mail);
            
            $to = "ismailabdulqudus063@gmail.com";
            $from = "support@farmapropos.com";
            $subject = "Account Email Verification";
            // $message = "please verify";
            
            // Compose a simple HTML email message 
            $message = '<html><body>';
            $message .= '<h1 style="color:#f40;">Verify Your Email from farmapopos.com!</h1>';
            $message .= '<p style="color:#080;font-size:18px;">Please click the Link below to 
            verify your Account </p>';
            $message .= "<a 
            class='btn btn-success btn-lg'
            href='https://farmapropos.com/verify_email?vkey=$vkey'>Verify Email</a>";
            $message .= '</body></html>';
            
            // To send HTML mail, the Content-type header must be set
            $headers  = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
            
            // Create email headers
            $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
            
            mail($to, $subject, $message, $headers);
            // EMAIL SEND ABOVE HERE
            
            return $message;
        }

    }

}

$mail = new MAIL;
