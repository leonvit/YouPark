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
	<title>User Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav2.js"></script>
	<div class="container">
		<br>
		<h1 class="text-center mb-4">Register:</h1>
		<form id="register-form">
			<div class="form-group">
				<label for="username">Username:</label>
				<input type="text" class="form-control" id="username" name="username" required>
			</div>

			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number (+30):</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>

			<div class="form-group">
				<label for="car-color">Car Color:</label>
				<select class="form-control" id="car-color" name="car-color" required>
					<option value="">Select Car Color</option>
					<option value="red">Red</option>
					<option value="blue">Blue</option>
					<option value="green">Green</option>
					<option value="yellow">Yellow</option>
					<option value="black">Black</option>
					<option value="white">White</option>
				</select>
			</div>

			<div class="form-group">
				<label for="car-type">Car Type:</label>
				<select class="form-control" id="car-type" name="car-type" required>
					<option value="">Select Car Type</option>
					<option value="small-car">Small Car</option>
					<option value="medium-car">Medium Car</option>
					<option value="jeep">Jeep</option>
				</select>
			</div>
			<div class="text-center">
<div id="error"></div>
			<button type="submit" class="btn btn-primary btn-lg">Sign Up</button></div>
		</form>
	</div>
	<br><p class="text-center">Already have an account? <a href="login.php">Log in now</a>.</p>

	<script>
		// Get the form element
const registerForm = document.querySelector('#register-form');

// Add an event listener for the form submission
registerForm.addEventListener('submit', function(event) {
  // Prevent the default form submission behavior
  event.preventDefault();

  // Get the form data
  const formData = new FormData(registerForm);
  const queryString = new URLSearchParams(formData).toString();
  window.location.replace("/userbase/phone_verify.php?" + queryString);
});
/*
  // Create an AJAX request
  const xhr = new XMLHttpRequest();
  xhr.open('POST', '/php/register.php');
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Handle the AJAX response
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
		response = JSON.parse(xhr.responseText);
		if (response.success) {
			window.location.replace("/userbase/login.php"); }
		else {
			document.getElementById("error").innerHTML = "<div class=\"alert alert-danger\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>"+response.error+"</div>";

		}

		}
      } else {
        console.log('Error: ' + xhr.status);
      }
    
  };

  // Send the form data
  xhr.send(new URLSearchParams(formData));
});
*/

	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</body>
</html>