<?php
$conn = new mysqli("localhost", "root", "", "flickseat_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM movie WHERE movie_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Movie deleted successfully!');
                window.location.href='view_movies.php';
              </script>";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
