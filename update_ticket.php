<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "flickseat_db");
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'DB connection failed']));
}

$ticket_id = $_POST['ticket_id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE ticket_id = ?");
$stmt->bind_param("si", $status, $ticket_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Status updated.']);
} else {
    echo json_encode(['success' => false, 'error' => 'Update failed.']);
}

$stmt->close();
$conn->close();
