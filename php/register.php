<?php
// Define database connection constants
define('DB_HOST', 'server12.cretaforce.gr');
define('DB_USER', 'tasos_db');
define('DB_PASS', '4914db6ed8e3559107262d2199ff8fe0');
define('DB_NAME', 'tasos_db');

// Connect to the database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection failed
if (!$conn) {
    $error = "Connection failed: " . mysqli_connect_error();
    echo json_encode(array("success" => false, "error" => $error));
    exit();
}

// Check if the required fields are set
if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['car-color']) && isset($_POST['car-type'])) {
    
    // Escape the data to prevent SQL injection attacks
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $car_color = mysqli_real_escape_string($conn, $_POST['car-color']);
    $car_type = mysqli_real_escape_string($conn, $_POST['car-type']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username, email, or phone already exists
    $query = "SELECT * FROM users WHERE username='$username' OR email='$email' OR phone='$phone'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // A row already exists with the given username, email, or phone
        $error = "Username, email, or phone already taken";
        echo json_encode(array("success" => false, "error" => $error));
        exit();
    } else {
        // Insert the new data
        $query = "INSERT INTO users (username, password, email, phone, car_color, car_type) VALUES ('$username', '$hashed_password', '$email', '$phone', '$car_color', '$car_type')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            echo json_encode(array("success" => true));
            exit();
        } else {
            $error = "Error inserting data: " . mysqli_error($conn);
            echo json_encode(array("success" => false, "error" => $error));
            exit();
        }
    }
} else {
    $error = "Database Error";
    echo json_encode(array("success" => false, "error" => $error));
    exit();
}

// Close the database connection
mysqli_close($conn);

?>
