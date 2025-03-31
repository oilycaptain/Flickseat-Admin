<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flickseat_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$sql = "DELETE FROM notifications WHERE id = $id";
$conn->query($sql);
$conn->close();
?>