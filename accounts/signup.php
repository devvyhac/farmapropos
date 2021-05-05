<?php
// session_start();
// if (isset($_SESSION['id']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
//     header("Location: ../account");
// }

// if ($_SERVER['REQUEST_METHOD'] == "POST") {
//     require_once "process.php";
//     $process = new Process();
//     $result = $process->signup($_POST);
//     $error = $process->get_error();
// }

if (isset($_SESSION['id']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
    header("Location: ../account");
}

if (!empty($_SESSION['units']) || !empty($_SESSION['funding'])) {

    if (isset($_SESSION['id']) && isset($_SESSION['email']) && isset($_SESSION['username'])) {
        header("Location: ../account");
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        require_once "process.php";
        $process = new Process();
        $result = $process->signup($_POST, $_SESSION);
        $error = $process->get_error();
    }

} elseif (empty($_GET['funding']) && empty($_GET['units'])) {
    unset($_SESSION['funding']);
    unset($_SESSION['units']);
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        require_once "process.php";
        $process = new Process();
        $result = $process->signup($_POST);
        $error = $process->get_error();
    }

} 

function format($str){
    $str = filter_var($str, FILTER_SANITIZE_STRING);
    return $str;
}

?>

<?php require_once "includes/head.php" ?>

<?php if(isset($error["empty_err"])) : ?>
<h4 <?php echo (isset($error["empty_err"]) ? "class=error-msg" : ""); ?>><?php echo $error["empty_err"] ?></h4>

<?php elseif(isset($error["wrong_email"])) : ?>
<h4 <?php echo (isset($error["wrong_email"]) ? "class=error-msg" : ""); ?>><?php echo $error["wrong_email"] ?></h4>

<?php elseif(isset($error["name_error"])) : ?>
<h4 <?php echo (isset($error["name_error"]) ? "class=error-msg" : ""); ?>><?php echo $error["name_error"] ?></h4>

<?php elseif(isset($error["phone_error"])) : ?>
<h4 <?php echo (isset($error["phone_error"]) ? "class=error-msg" : ""); ?>><?php echo $error["phone_error"] ?></h4>

<?php elseif(isset($error["user_error"])) : ?>
<h4 <?php echo (isset($error["user_error"]) ? "class=error-msg" : ""); ?>><?php echo $error["user_error"] ?></h4>


<?php elseif(isset($error["password_error"])) : ?>
<h4 <?php echo (isset($error["password_error"]) ? "class=error-msg" : ""); ?>><?php echo $error["password_error"] ?></h4>

<?php else : ?>
<h4><?php echo ""; ?></h4>
<?php endif;?>

<h2 class="title bg-dark">Farm Apropos - Sign Up <i class="fa fa-user-plus"></i></h2>

<form method="POST" action="<?php echo "signup" ?>" >
<div class="row row-space">
<div class="col-2">
<div class="input-group">
    <label class="label">first name</label>
    <input class="input--style-4" type="text" name="first_name" 
    value="<?php isset($_POST['first_name']) ? print format($_POST['first_name']) : print ""; ?>" required title="Please Fill in This Field!">
</div>
</div>
<div class="col-2">
<div class="input-group">
    <label class="label">last name</label>
    <input class="input--style-4" type="text" name="last_name" 
    value="<?php isset($_POST['last_name']) ? print format($_POST['last_name']) : print ""; ?>" required title="Please Fill in This Field!">
</div>
</div>
</div>

<div class="row row-space">
<div class="col-2">
<div class="input-group">
    <label class="label">Email</label>
    <input class="input--style-4" type="text" name="email" 
    value="<?php isset($_POST['email']) ? print format($_POST['email']) : print ""; ?>" required title="Please Fill in This Field!">
</div>
</div>
<div class="col-2">
<div class="input-group">
    <label class="label">Phone Number</label>
    <input class="input--style-4" type="text" name="phone" 
    value="<?php isset($_POST['phone']) ? print format($_POST['phone']) : print ""; ?>" required title="Please Fill in This Field!">
</div>
</div>
</div>

<div class="row row-space">
<div class="col-2">
<div class="input-group">
    <label class="label">Birthday</label>
    <div class="input-group-icon">
        <input class="input--style-4 js-datepicker" type="text" name="birthday" 
        value="<?php isset($_POST['birthday']) ? print format($_POST['birthday']) : print ""; ?>" required title="Please Fill in This Field!">
        <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
    </div>
</div>
</div>
<div class="col-2">
<div class="input-group">
    <label class="label">Gender</label>
    <div class="rs-select2 js-select-simple select--no-search">
        <select name="gender" 
        value="<?php isset($_POST['gender']) ? print format($_POST['gender']) : print ""; ?>" required title="Please Fill in This Field!">
            <option selected="selected">Male</option>
            <option>Female</option>
        </select>
        <div class="select-dropdown"></div>
    </div>
</div>
</div>
</div>
<div class="row row-space">
<div class="col-2">
<div class="input-group">
    <label class="label" for="username">Username</label>
    <input class="input--style-4" type="text" name="username" required 
    value="<?php isset($_POST['username']) ? print format($_POST['username']) : print ""; ?>" title="Please Fill in This Field!">
</div>
</div>
<div class="col-2">
<div class="input-group">
    <label class="label" for="password">Password</label>
    <input class="input--style-4" type="password" name="password" required 
    value="<?php isset($_POST['password']) ? print format($_POST['password']) : print ""; ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
</div>
</div>
</div>

<div class="p-t-15 submit-area">
<button class="btn btn--radius-2 btn--blue" type="submit">Register</button>
<span>Already have an Account?</span>
<a href="login">Login</a>
</div>

<?php require_once "includes/footer.php" ?>