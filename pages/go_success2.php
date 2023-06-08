<!DOCTYPE html>
<html>
<head>
    <title>Car Information</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div id="navbar"></div>
  <script src="/nav/nav.js"></script><br>
    <div class="container text-center">

        <?php
        // Get latitude and longitude from the URL
        $latitude = $_GET['lat'];
        $longitude = $_GET['lng'];

        // Database connection details
        $servername = "server12.cretaforce.gr";
        $username = "tasos_db";
        $password = "4914db6ed8e3559107262d2199ff8fe0";
        $dbname = "tasos_db";

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check if the connection was successful
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Search for the username based on latitude and longitude in the coordinates table
        $coordinatesQuery = "SELECT username2 FROM coordinates WHERE latitude = $latitude AND longitude = $longitude";
        $coordinatesResult = $conn->query($coordinatesQuery);

        if ($coordinatesResult->num_rows > 0) {
            // Fetch the username
            $row = $coordinatesResult->fetch_assoc();
            $username = $row['username2'];

            // Search for car color and car type based on the username in the users table
            $usersQuery = "SELECT car_color, car_type FROM users WHERE username = '$username'";
            $usersResult = $conn->query($usersQuery);

            if ($usersResult->num_rows > 0) {
                // Fetch the car color and car type
                $row = $usersResult->fetch_assoc();
                $carColor = $row['car_color'];
                $carType = $row['car_type'];

                // Display the car information
                echo "<h3>$username has a $carColor $carType.</h3><br>";
                echo '<button class="btn btn-success" onclick="parked()">I have successfully left the parking spot</button>';
            } else {
                echo "<p>No car information found for the username: $username.</p>";
            }
        } else {
            echo "<p>No username found for the provided latitude and longitude.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>

    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function parked() {
            const urlParams = new URLSearchParams(window.location.search);
            const destinationLat = urlParams.get("lat");
            const destinationLng = urlParams.get("lng");
            // Add your desired JavaScript functionality here
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/php/pairing4.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Handle the response
                    window.location.href = "/";
                }
            };

            // Serialize the data object as URL-encoded parameters
            var data = new URLSearchParams();
            data.append('lat', destinationLat);
            data.append('lng', destinationLng);

            xhr.send(data.toString());
                }
    </script>
</body>
</html>
