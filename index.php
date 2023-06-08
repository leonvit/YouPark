<?php
include $_SERVER['DOCUMENT_ROOT'] . '/php/remember.php';
redirectIfNotLoggedIn();
$apiKey = "AIzaSyAKcsXA7Bi1x67KN5FgN10SuyIrzdh2EQM";

$user = $_SESSION['username'];

$servername = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$dbname = "tasos_db";




// Create a new MySQLi object
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("SELECT coins FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();

// Bind the result to a variable
$stmt->bind_result($coinCount);
$stmt->fetch();
$coins = $coinCount;

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>YouPark</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
      .top-right-corner {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        align-items: center;
      }

      .badge {
        border: 2px solid black;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: white;
        font-size: 24px;
        font-weight: bold;
        display: flex;
        align-items: center;
      }

      .badge img {
        max-height: 30px;
        margin-left: 6px;
      }
    </style>
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

    <div class="top-right-corner">
      <div class="badge">
       <?php echo $coins ?>
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Bitcoin.png" alt="Bitcoin Gold Coin">
      </div>
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
