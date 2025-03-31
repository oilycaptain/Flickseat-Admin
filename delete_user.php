<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "flickseat_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    // Sanitize and validate input
    $id = $conn->real_escape_string($_GET['id']);
    
    // First, check if user exists (optional but recommended)
    $check_query = "SELECT user_id FROM users WHERE user_id = '$id'";
    $result = $conn->query($check_query);
    
    if ($result->num_rows === 0) {
        // User doesn't exist
        $_SESSION['delete_message'] = "Error: User not found";
        header("Location: all_user.php?status=error");
        exit();
    }

    // Prepare delete statement (better security than direct query)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        // Success - check affected rows
        if ($stmt->affected_rows > 0) {
            $_SESSION['delete_message'] = "User deleted successfully";
            header("Location: all_user.php?status=success");
        } else {
            $_SESSION['delete_message'] = "No user was deleted";
            header("Location: all_user.php?status=warning");
        }
    } else {
        $_SESSION['delete_message'] = "Error deleting user: " . $conn->error;
        header("Location: all_user.php?status=error");
    }
    
    $stmt->close();
} else {
    $_SESSION['delete_message'] = "Invalid request: No user ID provided";
    header("Location: all_user.php?status=error");
}

$conn->close();
exit();
?>