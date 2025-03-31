<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));
    $error = '';

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['admin'] = $user['username'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid credentials!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form id="loginForm" method="POST" action="">
            <h2>Login</h2>
            <div class="form-group">
                <input type="text" id="loginUsername" name="username" placeholder="Username" required>
                <div id="loginUserError" class="validation-message"></div>
            </div>
            <div class="password-wrapper">
  <input type="password" id="loginPassword" name="password" placeholder="Password" required>
  <span class="toggle-password" data-target="loginPassword">👁️</span>
</div>

            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </form>
    </div>
    <script src="validation.js"></script>
</body>
</html>