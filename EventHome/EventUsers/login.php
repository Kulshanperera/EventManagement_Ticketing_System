<?php
require_once '../Config/config.php';

if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('adminDashboard.php');
    } else {
        redirect('../Events/dashBoard.php');
    }
}

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
        $sql = "SELECT id, username, email, password, role, status FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // Check if user is deactivated
            if ($user['status'] == 'deactivated') {
                $errors[] = "Your account has been deactivated. Please contact support.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] == 'admin') {
                    redirect('adminDashboard.php');
                } else {
                    redirect('../Events/dashBoard.php');
                }
            } else {
                $errors[] = "Invalid username or password";
            }
        } else {
            $errors[] = "Invalid username or password";
        }
        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Garden - Register</title>
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
    <link rel="stylesheet" href="../../Eventcss/login.css">
     <script src="../EventJavascript/Event.js"></script>
</head>
<body class="back">
    <!-- Header Navigation -->
    <header>
        <nav class="navbar">
            <a href="../homePage.php" class="logo">Event Garden</a>
        </nav>
    </header>
    <div class="container">
        <h2>Login</h2>
        
        <div class="info">
            <strong>Acounts:</strong><br>
            Admin - Username: admin<br>
            Admin - Password: admin123<br>
        <div class="link">
            Don't have an admin account? <a href="createAdmin.php">Create here</a>
        </div>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <div class="password-wrapper">
                <input type="password" id="password" name="password" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
                </div>
                <div class="forgot-link">
                    <a href="forgotPassword.php">Forgot Password?</a>
                </div>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="link">
            Don't have an account? <a href="register.php">Register here</a>
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
