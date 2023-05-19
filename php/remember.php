<?php
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Function to redirect users to the login page
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: /userbase/userpath.php');
        exit;
    }
}

// Check for the remember me token

if (!isLoggedIn() && isset($_COOKIE['YouParkCookie'])) {
    $token = $_COOKIE['YouParkCookie'];
    $_SESSION['username'] = $token; // Implement this function to retrieve the associated username
}
?>
