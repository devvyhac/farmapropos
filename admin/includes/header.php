<?php require_once "../backend/includes/db.inc.php"; 

if (!isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['display_name'])) {
    header("Location: login");
}

?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Sidebar 09</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
  </head>
  <body>
		
<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="img bg-wrap text-center py-4" style="background-image: url(images/bg_1.jpg);">
            <div class="user-logo">
        <div class="img" style="background-image: url(images/logo.jpg);"></div>
        <a href="action?logout=true">Logout</a>
                <h3><?php echo $_SESSION['display_name'] ?></h3>
            </div>
        </div>
        <ul class="list-unstyled components mb-5">
            <li <?php echo ($this->getAction() == "dashboard" || $this->getAction() == "" ? "class=active" : ""); ?>>
            <a href="dashboard"><span class="fas fa-desktop mr-3"></span> Dashboard</a>
            </li>
            <li <?php echo ($this->getAction() == "tables" ? "class=active" : ""); ?>>
                <a href="#" id="tables-toggle">
                    <span class="fa fa-th-large mr-3"></span> Tables 
                    <i class="fa fa-caret-down align-right"></i>
                </a>
                <div class="tables">
                    <form action="tables" method="POST">
                        <input type="hidden" name="table" value="farm_category">
                        <button name="submit">tables 1</button>
                    </form>
                    <form action="tables" method="POST">
                        <input type="hidden" name="table" value="farm_type">
                        <button name="submit">tables 2</button>
                    </form>
                    <form action="tables" method="POST">
                        <input type="hidden" name="table" value="farm_list">
                        <button name="submit">tables 3</button>
                    </form>
                    <form action="tables" method="POST">
                        <input type="hidden" name="table" value="farm_projects">
                        <button name="submit">tables 4</button>
                    </form>
                </div>
            </li>
            <li <?php echo ($this->getAction() == "users" || $this->getAction() == "" ? "class=active" : ""); ?>>
                <a href="users"><span class="fas fa-users mr-3"></span> Users</a>
            </li>
        </ul>
    </nav>

        <!-- Page Content  -->
    <div id="content" class="container-fluid">

        <div class="row m-0">
            <div class="col-12 p-0 heading-status bg-dark">
                <ul class="left">
                    <li><button id="sidebarCollapse"><span class="fa fa-stream"></span></button></li>
                </ul>
                <ul class="right">
                    <li class="wallet-info">
                    <a href="#"><b class="py-2">Balance: </b> <span> $67.07</span></a>
                    </li>
                    <li class="user-info"><a href="#">
                    <img src="images/logo.jpg" alt="">
                    </a></li>
                </ul>
            </div>
        </div>

        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="jumbtron jumbotron-lg text-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="..">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Account</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $this->getAction(); ?></li>
                        </ol>
                    </nav>
                </div>