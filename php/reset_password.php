<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Assuming you have already connected to the database and have necessary configurations for sending emails
$db_host = 'server12.cretaforce.gr';
$db_user = 'tasos_db';
$db_pass = '4914db6ed8e3559107262d2199ff8fe0';
$db_name = 'tasos_db';

// Retrieve the token from the URL
$token = $_GET['token'];

$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for connection errors
if ($db->connect_error) {
    die('Database connection failed: ' . $db->connect_error);
}

// Check if the token exists and is not expired
$currentTime = time();

$statement = $db->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiration > ?");
$statement->bind_param("si", $token, $currentTime);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows > 0) {
  // Token is valid, allow the user to reset the password
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate the new password
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password === $confirmPassword) {
      // Hash the new password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Update the user's password in the database
      $updateStatement = $db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiration = NULL WHERE reset_token = ?");
      $updateStatement->bind_param("ss", $hashedPassword, $token);
      $updateStatement->execute();

      // Display a success message or redirect the user to a confirmation page
      echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-success text-center\" role=\"alert\">Your Password Has been Successfully Updated. <a href=\"/userbase/login.php\">Please Login.</a></div>";
    } else {
      // Display an error message indicating that passwords do not match
      echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-danger text-center\" role=\"alert\">Passwords do not match. Please try again.</div>";
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
    <br>
  <h1>Reset Password</h1>
  <br><br>
  <form method="POST" action="">
    <label for="password">New Password:</label>
    <input type="password" id="password" name="password" required><br>
    <br>

<label for="confirm_password">Confirm Password:</label>
<input type="password" id="confirm_password" name="confirm_password" required><br>
<br><br>
<input type="submit" class="btn btn-primary btn-md" value="Change Password">
</form>
</div>
</body>
</html>
<?php
} else {
// Invalid or expired token, display an error message or redirect the user to an error page
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-danger text-center\" role=\"alert\">The password reset link is invalid or it has expired after 1 hour. <a href=\"/userbase/forgot.php\">Get another one.</a></div>";
}
?>

