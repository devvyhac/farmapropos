<?php

require_once "/home/farmapro/public_html/backend/includes/db.inc.php";
session_start();

// INHERITING THE PROPERTIES AND METHODS FROM DATABASE CLASS

class TOPUP extends DATABASE{
    private $error;
    public function __construct() {
        parent::__construct();
        $this->error = null;
    }
}

// GETTING ALL THE PENDING PROJECTS TO BE SET TO COMPLETED;
$get_value_sql = "SELECT pay.pay_id, pay.funding, pay.payment_status,
                  pay.date_payed, pay.price, pay.txref, pay.units, 
                  f.*, p.started_on, p.will_end_on, p.tenor, p.project_status, 
                  u.* FROM payments AS pay INNER JOIN funding AS f
                  ON pay.funding = f.funding_id AND pay.payment_status = 'success'
                  INNER JOIN farm_projects as p ON f.project = p.id AND p.project_status = 'pending'
                  INNER JOIN users AS u ON pay.user_id = u.user_id";


// CREATING A NEW TOPUP CLASS TO HANDLE ALL THE DATABASE FUNCTIONALITIES
$top_up = new TOPUP;

// FETCHING THE ACTUAL DATA FROM THE PENDING PROJECT USING ITS ABOVE SQL
$stmt = $top_up->connect->prepare($get_value_sql);
$stmt->execute();
$result = $stmt->fetchAll();
print_r($result);

// LOOPING THROUGH EACH PENDING PROJECTS TO CHECK IF ANY OF THEM HAS BEEN DUE FOR COMPLETION
foreach($result as $key => $value){
    
    // GETTING THE TIME DIFFERENCE TO SEE IF THE PRESET DATE IS NOW REACHED OR IS NOW A PAST
    $diff = (strtotime($result[$key]->will_end_on) - strtotime(date("y-m-d h:i:s",time())));
    // echo $result[$key]->user_id;
    // echo $diff."<br>";
    
    // CHECKING IF THE PROJECT STATUS IS PENDING BEFORE PROCEEDING WITH THE DATABASE QUERY
    if($result[$key]->project_status === "pending"){
        
        // CHECKING IF THE TIME HAS BEEN REACHED OR THE TIME IS NOW IN THE PAST
        if($diff <= 0){
            
            // SETTING UP THE REQUIRED SQL QUERY STRINGS FOR THE WALLET UPDATE FUNCTIONALITIES
            // $get_balance = "SELECT * FROM wallet where user_id = :user_id";
            $wallet_top_up_sql = "UPDATE wallet SET balance = balance + :balance where user_id = :user_id";
            $complete_project_sql = "UPDATE farm_projects set project_status = 'completed' where id = :id";
            
            // GETTING THE INITIAL BALANCE OF EACH USER
            // $balance = $top_up->connect->prepare($get_balance);
            // $balance->bindParam(":user_id", $result[$key]->user_id);
            // $balance->execute();
            // $balance = $balance->fetch();
            
            // print_r($balance);
            
            // CALCULATING THE NEW BALANCE FOR VALUE ASSIGNMENT IN THE WALLET BALANCE
            $top_up_balance = ($result[$key]->price + get_roi($result[$key]->price, $result[$key]->roi));
            
            // GIVING VALUES IN THE WALLET DATABASE 
            $wallet_top_up = $top_up->connect->prepare($wallet_top_up_sql);
            $wallet_top_up->bindParam(":balance", $top_up_balance);
            $wallet_top_up->bindParam(":user_id", $result[$key]->user_id);
            $wallet_top_up->execute();
            
            // SETTING PROJECT STATUS = COMPLETE
            $complete_project = $top_up->connect->prepare($complete_project_sql);
            $complete_project->bindParam(":id", $result[$key]->project);
            $complete_project->execute();
        }
    }
}

// THIS IS THE FUNCTION THAT CALCULATES THE RATE OF THE INVESTMENT;
function get_roi ($amount, $perc) {
    return ($amount * ($perc / 100));
}

header("Location: /home");

// function gen_rand_char(){
//     // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
//     // // Output: 54esmdr0qf
//     // echo substr(str_shuffle($permitted_chars), 0, 10);
    
//     $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     // Output: video-g6swmAP8X5VG4jCi.mp4
//     return substr(str_shuffle($permitted_chars), 0, 10);
// }






