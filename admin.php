<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found or session expired.");
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ✅ Check if the new username already exists (EXCLUDING current user)
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND id != :id");
    $stmt->bindParam(':username', $new_username);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();

    if ($stmt->fetch()) {
        $errorMessage = 'Username already taken. Please choose another.';
    } else {
        // ✅ If username is available, update the profile
        $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
        $stmt->bindParam(':username', $new_username);
        $stmt->bindParam(':password', $new_password);
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            $_SESSION['admin'] = $new_username;
            $successMessage = 'Profile updated successfully!';
        } else {
            $errorMessage = 'Error updating profile.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Flickseat</title>
</head>
<body>
<aside class="sidebar">
        <header class="sidebar-header">
            <a href="#" class="header-logo">
                <img src="Logo.png" alt="logo">
            </a>
            <button class="toggler sidebar-toggler">
                <span class="material-symbols-rounded">chevron_left</span>
            </button>
            <button class="toggler menu-toggler">
                <span class="material-symbols-rounded">menu</span>
            </button>
        </header>
        <nav class="sidebar-nav">
            <ul class="nav-list primary-nav"> 
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-symbols-rounded">dashboard</span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <span class="nav-tooltip">Dashboard</span>
                </li>
                <li class="nav-item">
                    <a href="reservation.php" class="nav-link">
                        <span class="material-symbols-rounded">book</span>
                        <span class="nav-label">Reservations</span>
                    </a>
                    <span class="nav-tooltip">Reservations</span>
                </li>
                <li class="nav-item">
                    <a href="notif.html" class="nav-link">
                        <span class="material-symbols-rounded">notifications</span>
                        <span class="nav-label">Notifications</span>
                    </a>
                    <span class="nav-tooltip">Notifications</span>
                </li>
                <li class="nav-item">
                    <a href="food.php" class="nav-link">
                        <span class="material-symbols-rounded">fastfood</span>
                        <span class="nav-label">Food & Drinks</span>
                    </a>
                    <span class="nav-tooltip">Pagkain</span>
                </li>
            </ul>
            <ul class="nav-list secondary-nav">
            <li class="nav-item">
                    <a href="settings.html" class="nav-link">
                        <span class="material-symbols-rounded">settings</span>
                        <span class="nav-label">Settings</span>
                    </a>
                    <span class="nav-tooltip">Settings</span>
                </li>
                <li class="nav-item">
                    <a href="admin.php" class="nav-link">
                        <span class="material-symbols-rounded">account_circle</span>
                        <span class="nav-label">Admin</span>
                    </a>
                    <span class="nav-tooltip">Account</span>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <span class="material-symbols-rounded">logout</span>
                        <span class="nav-label">Logout</span>
                    </a>
                    <span class="nav-tooltip">Logout</span>
                </li>
            </ul>
        </nav>
    </aside>


    <!-- Toast Notification -->
<div id="toast" class="toast"><?php echo $successMessage ?: $errorMessage; ?></div>

<div class="main-content">
    <div class="form-container">
        <form id="updateForm" method="POST" action="">
            <h1>Update Profile</h1>

            <!-- Username Field -->
            <div class="input-group">
                <label>Change Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <small id="usernameFeedback" class="feedback-message">
                    <?php echo $errorMessage ?: ''; ?>
                </small>
            </div>

            <!-- Password Field -->
            <div class="input-group">
    <label>Change Password:</label>
    <div class="password-wrapper">
        <input type="password" id="password" name="password" placeholder="New Password" required>
        <span id="togglePassword" class="toggle-password">&#128065;</span>
    </div>
    <small id="passwordFeedback" class="feedback-message"></small>
</div>

            <button class="btn2" type="submit">Update</button>
        </form>
    </div>
</div>

    <script src="script.js"></script>
</body>
</html>