
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flickseat_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE notifications SET read_status = 1";
$conn->query($sql);
$conn->close();
?>