<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Movie</title>
  <style>
    body {
      background-color: #1a1a1a;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .form-container {
      background: #2e2e2e;
      padding: 2rem 3rem;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(128, 0, 128, 0.6);
      max-width: 600px;
      width: 100%;
    }

    .form-container h2 {
      color: #c77dff;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 0.6rem;
      border-radius: 8px;
      border: none;
      background: #1c1c1c;
      color: white;
    }

    .form-group input[type="submit"] {
      background: #7b2cbf;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-group input[type="submit"]:hover {
      background: #9d4edd;
    }

    .form-group select {
      background-color: #1c1c1c;
    }
  </style>
</head>
<body>
  <form method="POST" class="form-container" onsubmit="return validateForm()">
    <h2>Add New Movie</h2>
    
    <div class="form-group"><label>Title</label><input type="text" name="title" required></div>
    <div class="form-group"><label>Genre</label><input type="text" name="genre" required></div>
    <div class="form-group"><label>Release Date</label><input type="date" name="release_date" required></div>
    <div class="form-group"><label>Duration</label><input type="text" name="duration" placeholder="e.g. 1h 45m" required></div>
    <div class="form-group"><label>Overview</label><textarea name="overview" rows="3" required></textarea></div>
    <div class="form-group"><label>Rating</label><input type="number" step="0.1" name="rating" min="0" max="10" required></div>
    <div class="form-group"><label>TMDb ID</label><input type="number" name="tmdb_id" required></div>
    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <option value="now showing">Now Showing</option>
        <option value="coming soon">Coming Soon</option>
      </select>
    </div>
    <div class="form-group"><label>Movie Price</label><input type="number" name="movie_price" required></div>
    <div class="form-group"><input type="submit" name="submit" value="Add Movie"></div>
  </form>

  <script>
    function validateForm() {
      const rating = document.querySelector('input[name="rating"]').value;
      if (rating < 0 || rating > 10) {
        alert("Rating must be between 0 and 10.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
  $conn = new mysqli("localhost", "root", "", "flickseat_db");

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("INSERT INTO movie (title, genre, release_date, duration, overview, rating, tmdb_id, status, movie_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param(
    "sssssdssi",
    $_POST['title'],
    $_POST['genre'],
    $_POST['release_date'],
    $_POST['duration'],
    $_POST['overview'],
    $_POST['rating'],
    $_POST['tmdb_id'],
    $_POST['status'],
    $_POST['movie_price']
  );

  if ($stmt->execute()) {
    echo "<script>alert('Movie added successfully!');</script>";
  } else {
    echo "<script>alert('Failed to add movie.');</script>";
  }

  $stmt->close();
  $conn->close();
}
?>
