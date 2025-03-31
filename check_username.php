<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);

    if (!empty($username)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->fetch()) {
            echo 'taken'; // Username already taken
        } else {
            echo 'available'; // Username is available
        }
    } else {
        echo 'invalid';
    }
} else {
    echo 'invalid_request';
}
