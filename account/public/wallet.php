<?php require_once "includes/header.php";

class WALLET extends DATABASE{
    public function __construct(){
        parent::__construct();
    }
}

$sql = "Select * from wallet where user_id = :user_id";

$transaction = new WALLET();

$stmt = $transaction->connect->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['id']);
$stmt->execute();
$result = $stmt->fetchAll();

var_dump($result);

// $diff = abs(strtotime($result[1]->end_on) - strtotime(time()));

// $date1 = "2007-03-24";
// $date2 = "2009-06-26";

// $diff = abs(strtotime($result[1]->end_on) - strtotime(date("y-m-d")));

// $years = floor($diff / (365*60*60*24));
// $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
// $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

// printf("%d years, %d months, %d days\n", $years, $months, $days);

?>


<div class="wallet-wrapper">
    <div class="wallet-header">
        <p>how was it...</p>
    </div>
    <div class="wallet-body">
        wallet body
    </div>
    <div class="wallet-footer">
        wallet footer
    </div>
</div>
<?php require_once "includes/footer.php" ?>