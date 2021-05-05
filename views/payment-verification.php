<?php

// if(!isset($_SESSION['payment']['funding']) || !isset($_SESSION['payment']['price'])){
//     header("Location: ../farms");
// }

require_once "includes/head.php";

// $msg = $_GET['status'];

// echo $msg;
// print_r($_SESSION);

$curl = curl_init();
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';
if(!$reference){
  die('No reference supplied');
}

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Bearer sk_test_2191c1d89190d49843f52326c4b4957e3d78c1b0",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
    // there was an error contacting the Paystack API
  die('Curl returned error: ' . $err);
}

$tranx = json_decode($response);

if(!$tranx->status){
  // there was an error from the API
  die('API returned error: ' . $tranx->message);
}

if('success' == $tranx->data->status){
  // transaction was successful...
  // please check other things like whether you already gave value for this ref
  // if the email matches the customer who owns the product etc
  // Give value
//   echo "<h2>Thank you for making a purchase. Your file has bee sent your email.</h2>";

    $status = $tranx->data->status;
    $payment_info = $tranx->data->metadata;
    
    $funding_sql = "SELECT cat.category_name, type.type, p.tenor, p.project_status,
    f.*, farm.name, farm.image, farm.state, farm.city FROM farm_category AS cat 
    INNER JOIN farm_type AS type ON cat.category_id = type.category 
    INNER JOIN farm_projects AS p ON type.type_id = p.farm_type
    INNER JOIN funding AS f ON p.id = f.project AND f.funding_id = :funding
    INNER JOIN farm_list AS farm";
    
    // $pricing_sql = "SELECT pack.package_name, pack.description, pack.roi, p.*, f.category_name 
    // FROM package AS pack 
    // INNER JOIN pricing AS p ON pack.id = p.package and p.id = :pricing
    // INNER JOIN farm_category AS f ON p.category = f.category_id";
    
    class VERIFY extends DATABASE{
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
    }
    
    if ($status === "success"){
        $price = $_SESSION['payment']['price'];
        $_SESSION['payment']['status'] = $status;
        // print_r($_SESSION['payment']);
        
        $sql_payment = "INSERT INTO payments (funding, user_id, payment_status, user_email, user_name, price, units, roi, txref) VALUES (:funding, :user_id, :status, :email, :username, :price, :units, :roi, :txref);";
        $transaction_sql = "INSERT INTO transactions (user_id, type, amount, status, txref) VALUES (:user_id, :type, :amount, :status, :txref);";
        
        $verify_duplicate_payment = "Select * from payments where txref = :txref";
        $verify_duplicate_transaction = "Select * from transactions where txref = :txref";
        
        
        
        $type = "direct";
        
        $verify = new VERIFY();
    
        if(isset($_SESSION['payment']['funding']) && isset($_SESSION['payment']['price'])){
            $funding = $verify->select_item($funding_sql, ":funding", $_SESSION['payment']['funding']);
            // $pricing = $verify->select_item($pricing_sql, ":pricing", $_SESSION['payment']['pricing']);
        }
    
        
        try{
            $upload_pay = $verify->connect->prepare($sql_payment);
            $transact = $verify->connect->prepare($transaction_sql);
            
            $verify_payment = $verify->connect->prepare($verify_duplicate_payment);
            $verify_transaction = $verify->connect->prepare($verify_duplicate_transaction);
            
            $upload_pay->bindParam(":funding", $_SESSION['payment']['funding']);
            $upload_pay->bindParam(":user_id", $_SESSION['id']);
            $upload_pay->bindParam(":status", $_SESSION['payment']['status']);
            $upload_pay->bindParam(":email", $_SESSION['email']);
            $upload_pay->bindParam(":username", $_SESSION['username']);
            $upload_pay->bindParam(":price", $_SESSION['payment']['price']);
            $upload_pay->bindParam(":units", $_SESSION['payment']['units']);
            $upload_pay->bindParam(":roi", $_SESSION['payment']['roi']);
            $upload_pay->bindParam(":txref", $tranx->data->reference);
            
            // STORING TRANSACTION DETAILS IN TRANSACTION TABLE
            $transact->bindParam(":user_id", $_SESSION['id']);
            $transact->bindParam(":type", $type);
            $transact->bindParam(":amount", $_SESSION['payment']['price']);
            $transact->bindParam(":status", $_SESSION['payment']['status']);
            $transact->bindParam(":txref", $tranx->data->reference);
            
            // VERIFYING IF THERE IS ANY DUPLICATE PAYMENT OR TRANSACTION IN THE DATABASE TABLES
            $verify_payment->bindParam(":txref", $tranx->data->reference);
            $verify_transaction->bindParam(":txref", $tranx->data->reference);
            
            // GIVING VALUES IN THE DATABASE BELOW
            $success_upload = 0;
            if ($res = $verify_payment->execute() && $res2 = $verify_transaction->execute()) {
                // var_dump($verify_payment->rowCount());
                // echo "   ";
                // var_dump($verify_transaction->rowCount());
                if($verify_payment->rowCount() > 0 && $verify_transaction->rowCount() > 0) {
                    throw new Exception("Sorry, payment has already been made");
                    return;
                }
                else{
                    if($upload_pay->execute()){
                    $transact->execute();
                    } else {
                        // echo "there is some error here. please check it now.";
                        throw new Exception("Sorry, Unable To Access the Database at the Moment!");
                    }
                }
            }
            else {
                throw new Exception("Sorry, an error just occured. Please Try again!");
            }
        } catch (Exception $e) {
            $verify->setError($e->getMessage());
        }
    } else {
        die("the transaction was unsuccessful, please try again!");
    }
    
    $error = $verify->getError();

}

?>

<style>
    .card-body p{
        font-size: .9rem;
        margin: 0;
        margin-bottom: .2rem;
        color: #0eaa32;
        background: #f7f7f7;
        padding: .5rem;
        text-transform: capitalize;
    }
    
    .card-body p:nth-child(even){
        background: #f9f9f9;
    }
    
    .card-body p > span:first-child{
        font-weight: bolder;
        color: black;
        opacity: .6;
    }
    
    .card-header{
        background-color: #0eaa32;
    }
    
    .card-header h4{
        color: white;
    }
    
    .card-header h4 > span{
        float: right;
        text-transform: uppercase;
    }
    
    .card-body p > span:last-child{
        float: right;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card my-3">
                <div class="card-header">
                    <h4 class="mb-0">Thank You For Investing <i class="fa fa-smile"></i></h4>
                </div>
                <div class="card-body">
                    <p><span>You Invested in:</span> <span><?php echo $funding->type; ?> Project</span></p>
                    <p><span>Pricing:</span> <span>NGN-<?php echo $price; ?></span></p>
                    <p><span>Return On Investment:</span> <span><?php echo $funding->roi; ?>%</span></p>
                    <p><span>Project Duration:</span> <span><?php echo $funding->tenor; ?> Months</span></p>
                    <p><span>Farm Category:</span> <span><?php echo $funding->category_name; ?></span></p>
                    <p><span>Project Description:</span> <span><?php echo $funding->type; ?> farm production</span></p>
                </div>
                <div class="card-footer bg-light"><a href="../farms" class="btn btn-danger btn-sm">Return 
                <i class="fa fa-home"></i></a></div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>