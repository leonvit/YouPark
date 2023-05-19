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
  </head>
  <body>
  <div id="navbar"></div>
  <script src="/nav/nav.js"></script>

    <br><br>
    <div class="container-fluid">
      <h4 class="text-center text-secondary">Hi <?php echo $username; ?>!</h4>
      <h2 class="text-center display-5">Welcome Back!</h2>
    </div>
    <div class="container">
      <br>
      <table class="table text-center">
        <tbody>
          <tr>
            <td>
              <a href="pages/search.php"><img src="images/search.png" alt="Your first image description" class="img-fluid"  style="max-width: 100px;"></a>
            </td>
            <td>
              <a href="pages/go.php"><img src="images/go.png" alt="Your second image description" class="img-fluid"  style="max-width: 100px;"></a>
            </td>
          </tr>
          <tr>
            <td>
              <a href="pages/search.php"><h4>Search for PARKING</h4></a>
            </td>
            <td>
              <a href="pages/go.php"><h4>Register your SPOT</h4></a>
            </td>
          </tr>
        </tbody>
      </table>
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
