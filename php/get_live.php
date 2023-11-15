<?php
// Replace these with your actual database credentials
$hostname = "server12.cretaforce.gr";
$username = "tasos_db";
$password = "4914db6ed8e3559107262d2199ff8fe0";
$database = "tasos_db";

// Create a connection to the database
$conn = new mysqli($hostname, $username, $password, $database);

// Check for a connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data for latitude and longitude
$latitude = $_POST['lat'];
$longitude = $_POST['lng'];

// Prepare a SQL query to retrieve user_lat and user_long based on latitude and longitude
$sql = "SELECT user_lat, user_long FROM coordinates WHERE latitude = ? AND longitude = ?";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in SQL statement: " . $conn->error);
}

// Bind the parameters
$stmt->bind_param("dd", $latitude, $longitude);

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_lat = $row['user_lat'];
        $user_long = $row['user_long'];
        $response = array(
            'user_lat' => $user_lat,
            'user_long' => $user_long
        );

        // Convert the array to JSON
        $jsonResponse = json_encode($response);
        echo $jsonResponse;
    } else {
        echo "No matching coordinates found in the database.";
    }
} else {
    echo "Query execution failed: " . $stmt->error;
}

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>
