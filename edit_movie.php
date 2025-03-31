<?php
$conn = new mysqli("localhost", "root", "", "flickseat_db");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$movie = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("UPDATE movie SET title=?, genre=?, release_date=?, duration=?, overview=?, rating=?, tmdb_id=?, status=?, movie_price=? WHERE movie_id=?");
  $stmt->bind_param(
    "sssssdssii",
    $_POST['title'],
    $_POST['genre'],
    $_POST['release_date'],
    $_POST['duration'],
    $_POST['overview'],
    $_POST['rating'],
    $_POST['tmdb_id'],
    $_POST['status'],
    $_POST['movie_price'],
    $_POST['movie_id']
  );

  if ($stmt->execute()) {
    header("Location: view_movies.php?updated=1");
  } else {
    header("Location: view_movies.php?updated=0");
  }

  $stmt->close();
  $conn->close();
  exit();
}

if ($movie_id > 0) {
  $stmt = $conn->prepare("SELECT * FROM movie WHERE movie_id = ?");
  $stmt->bind_param("i", $movie_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $movie = $result->fetch_assoc();
  $stmt->close();
}

if (!$movie) {
  echo "<h2 style='color: white; text-align: center;'>Movie not found.</h2>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Movie</title>
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
  </style>
</head>
<body>
  <form method="POST" class="form-container">
    <h2>Edit Movie</h2>
    <input type="hidden" name="movie_id" value="<?= $movie['movie_id'] ?>">

    <div class="form-group"><label>Title</label><input type="text" name="title" value="<?= $movie['title'] ?>" required></div>
    <div class="form-group"><label>Genre</label><input type="text" name="genre" value="<?= $movie['genre'] ?>" required></div>
    <div class="form-group"><label>Release Date</label><input type="date" name="release_date" value="<?= $movie['release_date'] ?>" required></div>
    <div class="form-group"><label>Duration</label><input type="text" name="duration" value="<?= $movie['duration'] ?>" required></div>
    <div class="form-group"><label>Overview</label><textarea name="overview" rows="3" required><?= $movie['overview'] ?></textarea></div>
    <div class="form-group"><label>Rating</label><input type="number" step="0.1" name="rating" value="<?= $movie['rating'] ?>" min="0" max="10" required></div>
    <div class="form-group"><label>TMDb ID</label><input type="number" name="tmdb_id" value="<?= $movie['tmdb_id'] ?>" required></div>
    <div class="form-group">
      <label>Status</label>
      <select name="status">
        <option value="now showing" <?= $movie['status'] == 'now showing' ? 'selected' : '' ?>>Now Showing</option>
        <option value="coming soon" <?= $movie['status'] == 'coming soon' ? 'selected' : '' ?>>Coming Soon</option>
      </select>
    </div>
    <div class="form-group"><label>Movie Price</label><input type="number" name="movie_price" value="<?= $movie['movie_price'] ?>" required></div>
    <div class="form-group"><input type="submit" value="Update Movie"></div>
  </form>
</body>
</html>
