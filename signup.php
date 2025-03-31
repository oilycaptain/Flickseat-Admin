<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $success = $error = '';

    // Server-side validation
    if (strlen($username) < 4) {
        $error = "Username must be at least 4 characters";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            $success = "Account created successfully! Redirecting to login...";
            header("Refresh: 2; url=login.php");
        } else {
            $error = "Error creating account. Username may already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <form id="signupForm" method="POST" action="">
            <h2>Sign Up</h2>
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <div id="usernameError" class="validation-message">Username must be at least 4 characters</div>
            </div>
            <div class="form-group">
            <div class="password-wrapper">
  <input type="password" id="password" name="password" placeholder="Password" required>
  <span class="toggle-password" data-target="password">👁️</span>
</div>

                <div id="passwordError" class="validation-message">Password must be at least 8 characters</div>
                <div class="password-requirements">
                    <p>Password must contain:</p>
                    <ul>
                        <li id="req-length">At least 8 characters</li>
                        <li id="req-uppercase">At least one uppercase letter</li>
                        <li id="req-number">At least one number</li>
                    </ul>
                </div>
            </div>
            <button type="submit">Sign Up</button>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
    <script src="validation.js"></script>
</body>
</html>