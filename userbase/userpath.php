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
  <title>Login or Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav2.js"></script>
<div class="container mt-5">

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Login</h5>
          <p class="card-text">Already have an account? Login below.</p>
          <a href="login.php" class="btn btn-primary btn-block">Login</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Register</h5>
          <p class="card-text">Don't have an account yet? Register below.</p>
          <a href="register.php" class="btn btn-success btn-block">Register</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
