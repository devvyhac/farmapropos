<?php

if (isset($_GET['logout'])) {
    session_unset();
    header("Location: ..");
}


?>