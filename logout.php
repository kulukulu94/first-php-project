<?php
session_start();
session_destroy(); // Destroying All Sessions
//unsetting session variables
unset($_SESSION["userId"]);
unset($_SESSION["userId2"]);
// redirect to login page
header("Location: login/login.php");
?>