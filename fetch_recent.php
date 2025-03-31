<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP
$database = "flickseat_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch recent reservations
$sql = "SELECT * FROM recent_reservations";
$result = $conn->query($sql);

// Initialize array to hold data
$data = [];

if ($result->num_rows > 0) {
    // Fetch each row into the data array
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Output data as JSON (you can modify this to output HTML if needed)
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
