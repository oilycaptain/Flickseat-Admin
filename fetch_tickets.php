<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "flickseat_db");
if ($conn->connect_error) {
    die(json_encode(['error' => 'DB Connection Failed']));
}

$sql = "SELECT * FROM tickets ORDER BY purchase_date DESC";
$result = $conn->query($sql);

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

echo json_encode($tickets);
$conn->close();
