<?php
class ACCOUNT extends DATABASE{
    public function __construct(){
        parent::__construct();
    }
}

$account = new ACCOUNT();
$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $account->connect->prepare($sql);
$stmt->bindParam(":user_id", $_SESSION['id']);
if($stmt->execute()){
    $user = $stmt->fetch();
} else {
    echo "Unable to fetch User Info! Please Try Login in Again";
}

?>

<!doctype html>
<html lang="en">
  <head>
  	<title style="text-transform: capitalize; "><?php echo ucfirst($this->getAction()); ?></title>
  	<link rel="icon" href="../img/core-img/favicon.ico">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
    
  </head>
  <body>
		
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="img bg-wrap text-center py-4" style="background-image: url(images/<?php echo $user->bg_pic; ?>);">
            <i style="color: white;" id="nav-closer" class="fa fa-times mr-3"></i>
            <div class="user-logo">
                <div class="img" style="background-image: url(images/<?php echo $user->display_pic; ?>);"></div>
                <h3><?php echo ucfirst($user->firstname)." ".ucfirst($user->lastname); ?></h3>
            </div>
        </div>
        <ul class="list-unstyled components mb-5">
            <li <?php echo ($this->getAction() == "dashboard" || $this->getAction() == "" ? "class=active" : ""); ?>>
            <a href="dashboard"><span class="fas fa-desktop mr-3"></span> Dashboard</a>
            </li>
            <li <?php echo ($this->getAction() == "transactions" ? "class=active" : ""); ?>>
            <a href="transactions"><span class="fa fa-exchange-alt mr-3"></span> Transactions</a>
            </li>
            <li <?php echo ($this->getAction() == "investments" ? "class=active" : ""); ?>>
            <a href="investments"><span class="fas fa-donate mr-3"></span> Investments</a>
            </li>
            <li <?php echo ($this->getAction() == "open-projects" ? "class=active" : ""); ?>>
            <a href="open-projects"><span style="font-size: .8rem;" class="fa fa-box-open mr-3"></span> Open Projects</a>
            </li>
            <li <?php echo ($this->getAction() == "farm-projects" ? "class=active" : ""); ?>>
            <a href="farm-projects"><span style="font-size: .8rem;" class="fa fa-project-diagram mr-3"></span> My Projects</a>
            </li>
            
            <li <?php echo ($this->getAction() == "wallet" ? "class=active" : ""); ?>>
            <a href="wallet"><span class="fas fa-wallet mr-3"></span> Wallet</a>
            </li>
            
            <li <?php echo ($this->getAction() == "profile" ? "class=active" : ""); ?>>
            <a href="profile"><span class="fa fa-user-circle mr-3"></span> Account</a>
            </li>

            <li>
            <a href="action?logout=true"><span class="fa fa-sign-out mr-3 text-danger"></span> Logout</a>
            </li>
        </ul>
    </nav>

        <!-- Page Content  -->
    <div id="content" class="container-fluid">

        <div class="row m-0">
            <div class="col-12 p-0 heading-status" style="background-color: white">
                <ul class="left">
                    <li><button id="sidebarCollapse"><span class="fa fa-stream"></span></button></li>
                </ul>
                <ul class="right">
                    <!-- <li class="wallet-info">
                    <a href="#"><b class="py-2">Balance: </b> <span> $67.07</span></a>
                    </li> -->
                    <li class="user-info" style="box-shadow: 0 0 3px rgba(0,0,0,.3); border-radius: 50px; "><a href="#">
                    <img src="images/<?php echo $user->display_pic; ?>" alt="">
                    
                    </a>
                    
                    </li>
                    <span style="color: #bbb; padding-left: .5rem; text-transform: capitalize; font-weight: bolder; "><?php echo $_SESSION['username'];  ?></span>
                </ul>
            </div>
        </div>

        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="jumbtron jumbotron-lg text-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="..">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Account</a></li>
                        <li style="text-transform: capitalize; " class="breadcrumb-item active" aria-current="page"><?php echo $this->getAction(); ?></li>
                        </ol>
                    </nav>
                </div>