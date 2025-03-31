<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Movies</title>
  <style>
    body {
      background-color: #1a1a1a;
      font-family: 'Segoe UI', sans-serif;
      color: white;
      padding: 2rem;
    }

    h2 {
      color: #c77dff;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
      background: #2e2e2e;
      box-shadow: 0 0 15px rgba(128, 0, 128, 0.3);
    }

    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #444;
    }

    th {
      background-color: #1c1c1c;
      color: #c77dff;
    }

    tr:hover {
      background-color: #3a3a3a;
    }

    .actions a {
      padding: 0.5rem 1rem;
      text-decoration: none;
      border-radius: 6px;
      margin-right: 5px;
      font-weight: bold;
      display: inline-block;
    }

    .edit-btn {
      background: #7b2cbf;
      color: white;
    }

    .delete-btn {
      background: #ff4d6d;
      color: white;
    }
    #snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #333;
  color: #fff;
  text-align: center;
  border-radius: 8px;
  padding: 1rem;
  position: fixed;
  z-index: 100;
  left: 50%;
  bottom: 30px;
  font-size: 1rem;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
}

#snackbar.show {
  visibility: visible;
  opacity: 1;
}

  </style>
</head>

<?php if (isset($_GET['deleted'])): ?>
  <div id="snackbar">
    <?php echo $_GET['deleted'] == '1' ? '✅ Movie deleted successfully' : '❌ Failed to delete movie'; ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
  <div id="snackbar">
    <?php echo $_GET['updated'] == '1' ? '✅ Movie updated successfully' : '❌ Failed to update movie'; ?>
  </div>
<?php endif; ?>


<body>
  <h2>Movie List</h2>

  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Genre</th>
        <th>Release Date</th>
        <th>Duration</th>
        <th>Rating</th>
        <th>Status</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $conn = new mysqli("localhost", "root", "", "flickseat_db");
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT * FROM movie ORDER BY release_date DESC");
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
  <td>{$row['title']}</td>
  <td>{$row['genre']}</td>
  <td>{$row['release_date']}</td>
  <td>{$row['duration']}</td>
  <td>{$row['rating']}</td>
  <td>{$row['status']}</td>
  <td>{$row['movie_price']}</td>
  <td class='actions'>
    <a class='edit-btn' href='edit_movie.php?id={$row['movie_id']}'>Edit</a>
    <a class='delete-btn' href='#' onclick='confirmDelete({$row['movie_id']})'>Delete</a>
  </td>
</tr>";
        }

        $conn->close();
      ?>
    </tbody>
  </table>
  <script>
  window.onload = function() {
    var snackbar = document.getElementById("snackbar");
    if (snackbar) {
      snackbar.classList.add("show");
      setTimeout(function() {
        snackbar.classList.remove("show");
      }, 3000);
    }
  };
</script>

</body>
</html>

<script>
function confirmDelete(id) {
  if (confirm("Are you sure you want to delete this movie?")) {
    window.location.href = "delete_movie.php?id=" + id;
  }
}
</script>
