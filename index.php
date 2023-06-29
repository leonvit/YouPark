<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>YouPark</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      .card:hover {
        transform: scale(1.05);
      }

      .card-img-top {
        padding-top: 1.5rem;
      }
    </style>
    <style>
      /* Remove default link styling */
      a {
        text-decoration: none; /* Remove underline */
        color: inherit; /* Use the default text color */
      }

      /* Remove blue color on link click */
      a:active {
        color: inherit; /* Use the default text color */
      }

      /* Remove blue color and outline on link focus */
      a:focus {
        outline: none; /* Remove outline */
        color: inherit; /* Use the default text color */
      }

      /* Remove color change on hover */
      a:hover {
        text-decoration: none; /* Remove underline */
        color: inherit; /* Use the default text color */
      }
    </style>
  </head>
  <body>
    <div id="navbar"></div>
    <script src="/nav/nav3.js"></script>

    <br><br>
    <div class="container-fluid">
      <h4 class="text-center text-secondary">Hi <?php echo $username; ?>!</h4>
      <h2 class="text-center display-5">Welcome Back!</h2>
    </div>
    <div class="container">
      <br>
      <div class="row justify-content-center">
        <div class="col-md-6 mb-3">
          <a href="pages/search.php">
            <div class="card text-center">
              <img src="images/search.png" alt="Your first image description" class="card-img-top img-fluid mx-auto" style="max-width: 100px;">
              <div class="card-body">
                <h4 class="card-title">Search for PARKING</h4>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 mb-3">
          <a href="pages/go.php">
            <div class="card text-center">
              <img src="images/go.png" alt="Your first image description" class="card-img-top img-fluid mx-auto" style="max-width: 100px;">
              <div class="card-body">
                <h4 class="card-title">Register your SPOT</h4>
              </div>
            </div>
          </a>
        </div>
      </div>
      <br><br>
      <form action="/php/logout.php" method="post" onsubmit="return confirmLogout()">
        <div class="form-group row">
          <div class="col">
            <!-- Empty column to push the button to the right -->
          </div>
          <div class="col-auto">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      function confirmLogout() {
        return confirm("Are you sure you want to log out?");
      }
    </script>
  </body>
</html>
