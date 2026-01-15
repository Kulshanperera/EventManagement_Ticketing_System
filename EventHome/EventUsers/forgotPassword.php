<?php
require_once '../Config/config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Email is required";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+8 hours'));
            
            $update_sql = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($stmt, "ssi", $token, $expiry, $user['id']);
            mysqli_stmt_execute($stmt);
            
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/resetPassword.php?token=" . $token;
            
            $message = "Password reset instructions sent! <br><br>Reset link: <a href='$reset_link' target='_blank'>$reset_link</a>";
        } else {
            $message = "If an account exists with that email, a reset link has been sent.";
        }
    }
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
        <h2>Forgot Password</h2>
        <p class="subtitle">Enter your email to receive a password reset link</p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="your@email.com">
            </div>
            
            <button type="submit">Send Reset Link</button>
        </form>
        
        <div class="link">
            Remember your password? <a href="login.php">Login here</a>
        </div>
    </div>
        <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>