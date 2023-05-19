<?php
// Start the session
session_start();

// Check if user is already logged in, if yes then redirect to home page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: /");
    exit;
}

$servicesid = "VA636e546fbc72ee9db962c157aadf21c0";
$acctokens = "ACd7cdded2e659d39942855572efa16bf4:cfcbe0fb4b106c9a62c4d6ba1bdcfd04";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Phone Number Verification</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">

</head>
<body>

<div id="navbar"></div>
  <script src="/nav/nav.js"></script><br>
    <h1>Phone Number Verification</h1>

<br>
    <h3 id="phoneNumber"></h3>
    <p>Wrong Number? <a href="/userbase/register.php">Go Back</a></p>

    <hr>
    <button id="sendsms" class="btn btn-primary btn-md" onclick="sendCode()">Send Verification Code</button>

    <br><br>
    <div class="verification-section">
      <label for="verificationCode"><strong>Verification Code:</strong></label>
      <input type="text" id="verificationCodeInput" class="verification-input">
      <button onclick="verifyCode()"  class="btn btn-primary btn-md" id="verifyButton">Verify</button>
    </div>
  </div>
  <div id="error"></div>

  </div>
  <script>

// Get the form data from the query parameters
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

// Get the individual form values
const username = urlParams.get('username');
const password = urlParams.get('password');
const email = urlParams.get('email');
const phone = '+30'+urlParams.get('phone');
const carColor = urlParams.get('car-color');
const carType = urlParams.get('car-type');

const params = `username=${username}&password=${password}&email=${email}&phone=${phone}&car-color=${carColor}&car-type=${carType}`;

document.getElementById("phoneNumber").innerHTML=phone;
// Create an object with the form data


function sendCode() {
  document.getElementById("sendsms").disabled = true;
  var sendparams = new URLSearchParams();
  sendparams.append("To", phone);
  sendparams.append("Channel", "sms");
  fetch("https://verify.twilio.com/v2/Services/<?php echo $servicesid;?>/Verifications", {
  method: "POST",
  headers: {
    "Authorization": "Basic " + btoa("<?php echo $acctokens;?>"),
    "Content-Type": "application/x-www-form-urlencoded"
  },
  body: sendparams.toString()
})
.then(response => {
  if (response.json().status == "pending") {
    document.getElementById("error").innerHTML = "<div class=\"alert alert-success text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Code Sent! Check your phone!</div>";}
    else {
      document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Please try again later</div>";

    }
  })
.catch(error => {
  document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Please try again later</div>";
});
}

function verifyCode() {
  
  const enteredCode = document.getElementById('verificationCodeInput').value;
  verifyparams = new URLSearchParams();
  verifyparams.append("To", phone);
  verifyparams.append("Code", enteredCode);
  fetch("https://verify.twilio.com/v2/Services/<?php echo $servicesid;?>/VerificationCheck", {
  method: "POST",
  headers: {
    "Authorization": "Basic " + btoa("<?php echo $acctokens;?>"),
    "Content-Type": "application/x-www-form-urlencoded"
  },
  body: verifyparams.toString()
})

    .then(response => response.json())
    .then(data => {
      if (data.status=="approved") {
        var xhr = new XMLHttpRequest();
        var url = "/php/register.php";

        xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          window.location.replace("/userbase/login.php");

        } else {
          document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>"+response.error+"</div>";

        }
      }
    };
    
    xhr.send(params);
        
      }
      else if (data.status=="pending") {
        document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Wrong Code</div>";

      }
    })
    .catch(error => {
      document.getElementById("error").innerHTML = "<div class=\"alert alert-danger text-center\" role=\"alert\"><i class=\"bi bi-exclamation-circle-fill me-2\"></i>Code Expired - Try again later</div>";
    });


  
}




    
  </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script></body>
</html>
