<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flickseat_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT * FROM notifications WHERE id = $id";
$result = $conn->query($sql);
$notification = $result->fetch_assoc();

echo json_encode($notification);
$conn->close();
?>