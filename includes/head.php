<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <!-- Title -->
  <title><?php echo ucfirst($this->getAction()); ?> - Farm Apropos</title>
  <!-- Favicon -->
  <link rel="icon" href="img/core-img/favicon.ico">
  <!-- Core Stylesheet -->
  
  <link rel="stylesheet" href="style.css">
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
  <link rel="stylesheet" href="customlink.css">
  <link rel="stylesheet" href="css/custom/custom.css">
</head>

<body>
  <!-- Preloader -->
  <!-- <div class="preloader d-flex align-items-center justify-content-center">
    <div class="spinner"></div>
  </div> -->

  <!-- ##### Header Area Start ##### -->
  <header class="header-area">
    <!-- Top Header Area -->
    <div class="top-header-area">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="top-header-content d-flex align-items-center justify-content-between">
              <!-- Top Header Content -->
              <div class="top-header-meta">
                <p>Welcome to <span>Farm Apropos</span>, we hope you will enjoy our services and have good experience</p>
              </div>
              <!-- Top Header Content -->
              <div class="top-header-meta text-right">
                <a href="#" data-toggle="tooltip" data-placement="bottom" title="support@farmapropos.com"><i class="fa fa-envelope-o" aria-hidden="true"></i> <span>Email: support@farmapropos.com</span></a>
                <a href="#" data-toggle="tooltip" data-placement="bottom" title="+234 903 472 6175"><i class="fa fa-phone" aria-hidden="true"></i> <span>Call Us: +234 903 472 6175</span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navbar Area -->
    <div class="famie-main-menu">
      <div class="classy-nav-container breakpoint-off">
        <div class="container">
          <!-- Menu -->
          <nav class="classy-navbar justify-content-between" id="famieNav">
            <!-- Nav Brand -->
            <a href="/" class="nav-brand"><img src="img/core-img/farmlogo.png" alt=""></a>
            <!-- Navbar Toggler -->
            <div class="classy-navbar-toggler">
              <span class="navbarToggler"><span></span><span></span><span></span></span>
            </div>
            <!-- Menu -->
            <div class="classy-menu">
              <!-- Close Button -->
              <div class="classycloseIcon">
                <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
              </div>
              <!-- Navbar Start -->
              <div class="classynav">
                <ul>
                  <li <?php echo ($this->getAction() == "home" || $this->getAction() == "" ? "class=active" : ""); ?>><a href="home">Home</a></li>
                  <li <?php echo ($this->getAction() == "about" ? "class=active" : ""); ?>><a href="about">About Us</a></li>
                  <li <?php echo ($this->getAction() == "farms" ? "class=active" : ""); ?>><a href="farms">Farms</a></li>
                  <li <?php echo ($this->getAction() == "faq" ? "class=active" : ""); ?>><a href="faq">F.A.Q</a></li>
                  <li <?php echo ($this->getAction() == "contact" ? "class=active" : ""); ?>><a href="contact">Contact Us</a></li>
                </ul>
                    <?php if(isset($_SESSION['id'])): ?>
                    <a style="padding: 0; background-color: transparent; margin-left: 1rem; margin-right: 1rem;" href="account" id="sign-in">
                        <img style="width: 30px; border-radius: 50px;" src="account/images/<?php echo $_SESSION['dp'];  ?>" alt="">
                        <span style="color: #bbb; padding-left: .3rem; text-transform: capitalize; font-weight: bolder; "><?php echo $_SESSION['username'];  ?></span>
                    </a>
                    <?php else: ?>
                    <a href="accounts" id="sign-in" class="ml-1 mr-2"><span>SIGN IN</span></a>
                    <?php endif; ?>
                    
                    <a href="farms" id="invest-now"><span>INVEST NOW</span></a>
                
                <!-- Search Icon -->
                <!-- <div id="searchIcon">
                  <i class="icon_search" aria-hidden="true"></i>
                </div> -->
                <!-- Cart Icon -->
                <!-- <div id="cartIcon">
                  <a href="#">
                    <i class="icon_cart_alt" aria-hidden="true"></i>
                    <span class="cart-quantity">2</span>
                  </a>
                </div> -->
              </div>
              <!-- Navbar End -->
            </div>
          </nav>

          <!-- Search Form -->
          <div class="search-form">
            <form action="#" method="get">
              <input type="search" name="search" id="search" placeholder="Type keywords &amp; press enter...">
              <button type="submit" class="d-none"></button>
            </form>
            <!-- Close Icon -->
            <div class="closeIcon"><i class="fa fa-times" aria-hidden="true"></i></div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- ##### Header Area End ##### -->

  