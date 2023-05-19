<?php 
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete the cookie
$cookieName = 'YouParkCookie';
$cookiePath = '/';
setcookie($cookieName, '', time() - 3600, $cookiePath);

// Redirect to the login page
header("location: /userbase/login.php");
exit;
?>