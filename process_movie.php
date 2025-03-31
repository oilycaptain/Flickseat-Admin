<?php
require 'db.php'; // make sure it includes DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = (int) $_POST['duration'];
    $release_date = $_POST['release_date'];
    $status = $_POST['status'];
    $ticket_price = (float) $_POST['ticket_price'];
    $tmdb_id = (int) $_POST['tmdb_id'];
    $rating = (float) $_POST['rating'];
    $overview = $_POST['overview'];

    $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration, release_date, status, ticket_price, tmdb_id, rating, overview) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssids", $title, $genre, $duration, $release_date, $status, $ticket_price, $tmdb_id, $rating, $overview);

    if ($stmt->execute()) {
        header("Location: add.html?status=success");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>