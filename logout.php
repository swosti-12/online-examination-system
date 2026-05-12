<?php
session_start(); // Start the session
// Unset all session variables
$_SESSION = array();
// Destroy the session
session_destroy();
// Redirect to the login page or home page
header("Location: index.php"); // Redirect to login page after logout
exit();
?>