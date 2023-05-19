<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require  $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
// Database connection setup
$db_host = 'server12.cretaforce.gr';
$db_user = 'tasos_db';
$db_pass = '4914db6ed8e3559107262d2199ff8fe0';
$db_name = 'tasos_db';

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = 'mail.youpark.gr';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';

$mail->Username = 'noreply@youpark.gr';
$mail->Password = '4c7559322d4f463e91e94ebc331db517';





// Establish database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Retrieve the email from the form submission
$email = null;
$email = $_POST['email'];

$query = "SELECT username FROM users WHERE email = '$email'";
$statement = $conn->query($query);



if ($statement->num_rows > 0) {
    $token = bin2hex(random_bytes(32));
    $expirationTime = time() + (60 * 60);
    $query = "UPDATE users SET reset_token = '".$conn->real_escape_string($token)."', reset_expiration = '".$conn->real_escape_string($expirationTime)."' WHERE email = '".$conn->real_escape_string($email)."'";
    $statement = $conn->query($query);


    $resetLink = "https://youpark.gr/php/reset_password.php?token=" . urlencode($token);
    $query = "SELECT username FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $mail->setFrom('noreply@youpark.gr', 'YouPark Support');
    //$mail->addReplyTo('info@example.com', 'Reply To Name');
    $mail->addAddress($email);
    $mail->Subject = 'Account Recovery';
    $mail->Body = 'Hey '. $username . ' your passowrd reset link is: '.$resetLink;

    if (!$mail->send()) {
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-danger text-center\" role=\"alert\">Error Sending Email. ".$mail->ErrorInfo."</div>";

    } else {
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-success text-center\" role=\"alert\">A link to reset your password has been sent to your email.</div>";
    }

    
} else {
    // Email does not exist in the database
    echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css\"><br><br><br><div class=\"alert alert-danger text-center\" role=\"alert\">No account found with that email. <a href=\"/userbase/register.php\">Please Register.</a></div>";
}

// Close the database connection
$conn->close();
?>