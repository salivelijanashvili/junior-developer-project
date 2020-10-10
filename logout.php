<?php

// Initialize the session
session_start();
require_once 'layout/header.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy cookie
if (isset($_COOKIE["PHTARM"])) {
    setcookie("PHTARM", "", time() - 100, "/");
}
// Destroy the session.
session_destroy();
// Redirect to login page
header("location: index.php");
exit;
