<?php
require_once "set.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $set = new Set();
    $set->update($_POST);
}


?>

<form action="<?php echo filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_STRING); ?>" method="POST">
    <input type="text" name="username" placeholder="Username...">
    <input type="submit" name="submit" value="Submit">
</form>