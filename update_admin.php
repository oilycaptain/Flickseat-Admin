<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE admins SET username = :username, password = :password WHERE id = :id");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Admin details updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update admin details."]);
    }
}
