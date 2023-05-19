<?php
// Start the session
session_start();

// Check if user is already logged in, if yes then redirect to home page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css" media="screen">
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav.js"></script>
  

<div class="container text-center">
    <br>
    <h1>Forgot Password</h1>
    <br>
    <form method="post" action="/php/forgot_password.php">
        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>
        <input type="submit" class="btn btn-primary btn-md" value="Get Password Reset Link">
    </form>
<div id="error"></div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>