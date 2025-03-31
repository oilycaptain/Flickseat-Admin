<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flickseat_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = $conn->query($sql);
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}
echo json_encode($notifications);
$conn->close();
?>