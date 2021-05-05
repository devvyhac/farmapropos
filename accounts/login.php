<?php
// session_start();

// checking if the user is logged in before so that they can be redirected to the apropriate page.
if (isset($_SESSION['id']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
    header("Location: ../account");
}

// checking if the user has a pending investment they want to invest in before they had to login.
if (!empty($_SESSION['funding']) && !empty($_SESSION['units'])) {

    if (isset($_SESSION['id']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
        header("Location: ../account");
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        require_once "process.php";
        $process = new Process();
        $result = $process->login($_POST, $_SESSION);
        $error = $process->get_error();
    }

} elseif (empty($_GET['funding']) && empty($_GET['units'])) {
    unset($_SESSION['funding']);
    unset($_SESSION['units']);
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        require_once "process.php";
        $process = new Process();
        $result = $process->login($_POST);
        $error = $process->get_error();
    }

} 


function format($str){
    $str = filter_var($str, FILTER_SANITIZE_STRING);
    return $str;
}

?>

<?php require_once "includes/head.php" ?>

<?php if(isset($error["login_error"])) : ?>
<h4 <?php echo (isset($error["login_error"]) ? "class=error-msg" : ""); ?>><?php echo $error["login_error"] ?></h4>

<?php else : ?>
<h4><?php echo ""; ?></h4>
<?php endif;?>

<h2 class="title bg-dark">Farm Apropos - Login <i class="fa fa-lock"></i></h2>

<form method="POST" action="<?php echo "login" ?>" >
    <div class="row row-space">
        <div class="col-2">
            <div class="input-group">
                <label class="label" for="username">Username</label>
                <input class="input--style-4" type="text" name="username" required 
                value="<?php isset($_POST['username']) ? print format($_POST['username']) : print ""; ?>" placeholder="Username/Email" title="Please Fill in This Field!">
            </div>
        </div>
        <div class="col-2">
            <div class="input-group">
                <label class="label" for="password">Password</label>
                <input class="input--style-4" type="password" name="password" required 
                value="<?php isset($_POST['password']) ? print format($_POST['password']) : print ""; ?>" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
            </div>
        </div>
    </div>
    
    <!-- <div class="input-group">
        <label class="label">Subject</label>
        <div class="rs-select2 js-select-simple select--no-search">
            <select name="subject">
                <option disabled="disabled" selected="selected">Choose option</option>
                <option>Subject 1</option>
                <option>Subject 2</option>
                <option>Subject 3</option>
            </select>
            <div class="select-dropdown"></div>
        </div>
    </div> -->
    <div class="p-t-15 submit-area">
        <button class="btn btn--radius-2 btn--blue" type="submit">Login</button>
        <span>or</span>
        <a href="signup">Sign Up Instead?</a>
    </div>

<?php require_once "includes/footer.php" ?>