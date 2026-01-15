<?php
require_once '../Config/config.php';

if (isLoggedIn()) {
    redirect('../Events/dashBoard.php');
}

$error = '';
$success = '';
$valid_token = false;

if (!isset($_GET['token'])) {
    redirect('login.php');
}

$token = $_GET['token'];

// Verify token
$sql = "SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > CURRENT_TIMESTAMP";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $valid_token = true;
    $user = mysqli_fetch_assoc($result);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($password)) {
            $error = "Password is required";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match";
        } else {
            // Hash password and update database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user['id']);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Password reset successful. You can now login.";
            } else {
                $error = "Failed to reset password. Please try again.";
            }
        }
    }
} else {
    $error = "Invalid or expired reset token";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title></head>
        <link rel="stylesheet" href="../../Eventcss/adminDashboard.css">
         <link rel="stylesheet" href="../../Eventcss/forgotPassword.css">
             <link rel="stylesheet" href="../../Eventcss/homePage.css">
<header>
  <nav class="navbar">
    <a href="../homePage.php" class="logo">Event Garden</a>
</header>
<body class="back">
    <div class="container">
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
                <div class="link">
            You can now <a href="login.php">Login here</a>
            </div>
    <?php endif; ?>
    
    <?php if ($valid_token && !$success): ?>
        <form method="POST">
            <label>New Password:</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>

        <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>