<?php require_once "includes/header.php";
require_once "mail.php";

class DASHBOARD extends DATABASE{
    public function __construct(){
        parent::__construct();
    }
}

$dashboard = new DASHBOARD();
$item_sql = "SELECT pay.*, user.* FROM payments as pay
             INNER JOIN users as user ON user.user_id = pay.user_id AND user.user_id = :user_id";
$stmt = $dashboard->connect->prepare($item_sql);
$stmt->bindParam(":user_id", $_SESSION["id"]);
$data = [];
try{
    if($stmt->execute()){
        $data = $stmt->fetchAll();
    } else {
        throw new Exception("Unable to Access Data, Wrong Query Perhaps!");
    }
} catch (Exception $e) {
    $dashboard->error = $e->getMessage();
}

$investments = $data;


// $investments = $dashboard->get_data_by_user("payments", "user_id", "user_id", $_SESSION["id"]);
// print_r($investments);


// print_r($mailer);

// if (isset($_POST['send_mail'])){
//     $mailer = $mail->send_mail();
//     print_r($mailer);
// }

?>

<!--<form method="post" action="<?php echo 'dashboard';?>">-->
<!--    <input type="submit" value="Send Mail" name="send_mail" class="btn btn-success">-->
<!--</form>-->

<div class="container-fluid dashboard mt-3">
    <div class="row p-3">
        <div class="mb-4 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="inner-item">
                <a href="investments">
                    <div class="row m-0 p-0">
                    <div class="col-6 info">
                    <h6>investments</h6>
                    <p>Total No: <?php echo count($investments); ?></p>
                    </div>
                    <div class="col-6">
                    <img class="img img-responsive w-100" src="../img/core-svg/undraw_personal_finance_tqcd.svg" alt="">
                    </div>
                </div>
                </a>
            </div>
        </div>

        <div class="mb-4 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="inner-item">
                <a href="transactions">
                    <div class="row m-0 p-0">
                    <div class="col-6 info">
                    <h6>Transactions</h6>
                    <p>Total No: <?php echo count($investments); ?></p>
                    <p>
                        Amount: &#8358 <?php 
                        $total_amount = [];
                        foreach($investments as $key => $value){
                            array_push($total_amount, $value->price);
                        ?>
                        <?php } echo array_sum($total_amount);?>
                    </p>
                    </div>
                    <div class="col-6">
                    <img class="img img-responsive w-100" src="../img/core-svg/undraw_Credit_card_re_blml.svg" alt="">
                    </div>
                </div>
                </a>
            </div>
        </div>

        <div class="mb-4 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="inner-item">
                <a href="wallet">
                    <div class="row m-0 p-0">
                    <div class="col-6 info">
                    <h6>My Wallet</h6>
                    <p>
                        Amount: &#8358 
                        <?php echo array_sum($total_amount);?>
                    </p>
                    </div>
                    <div class="col-6">
                    <img class="img img-responsive w-100" src="../img/core-svg/undraw_wallet_aym5.svg" alt="">
                    </div>
                </div>
                </a>
            </div>
        </div>

        <div class="mb-4 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="inner-item">
                <div class="row m-0 p-0">
                    <div class="col-6 info">
                    <h6>Account</h6>
                    <p><a href="profile"><i class="fa fa-user-circle mr-2 text-primary"></i> Profile</a></p>
                    <p><a href="profile"><i class="fa fa-sign-out mr-2 text-danger"></i> Logout</a></p>
                    </div>
                    <div class="col-6">
                    <img class="img img-responsive w-100" src="../img/core-svg/undraw_account_490v.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php" ?>