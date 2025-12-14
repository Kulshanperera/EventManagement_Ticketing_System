<?php
require_once 'config.php';

// if (isLoggedIn()) {
//     if (isAdmin()) {
//         redirect('admin_dashboard.php');
//     } else {
//         redirect('index.php');
//     }
// }

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
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                if ($user['role'] == 'admin') {
                    redirect('homePage.php');
                } else {
                    redirect('homePage.php');
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
    <title>Event Garden - Login</title>
    <link rel="stylesheet" href="../eventcss/HomePage.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4; 
            min-height: 100vh;
        }
        
        /* Login Form Styles */
        .login-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 40px 20px;
        }
        
        .login-container { 
            background: linear-gradient(135deg, #cda1fdff 0%, #6781f8ff 100%);
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
        }
        
        .login-container h2 { 
            margin-bottom: 20px; 
            color: #333; 
            text-align: center; 
        }
        
        .form-group { 
            margin-bottom: 15px; 
        }
        
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
            font-weight: bold; 
        }
        
        input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: 14px; 
        }
        
        input:focus { 
            outline: none; 
            border-color: #2196F3; 
        }
        
        button { 
            width: 100%; 
            padding: 12px; 
            background: #2196F3; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
        }
        
        button:hover { 
            background: #0b7dda; 
        }
        
        .error { 
            background: #f44336; 
            color: white; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
        }
        
        .link { 
            text-align: center; 
            margin-top: 15px; 
        }
        
        .link a { 
            color: #53575aff; 
            text-decoration: none; 
        }
        
        .link a:hover { 
            text-decoration: underline; 
        }
    </style>
</head>
<body class="back">
        <!-- Header Navigation -->
<header>
    <nav class="navbar">
        <a href="homePage.php" class="logo">Event Garden</a>
        <ul class="nav-menu">
            <li><a href="homePage.php">Home</a></li>
            <li><a href="#">About</a></li>
        </ul>
        <div class="auth-buttons">
            <?php if (isLoggedIn()): ?>
                <!-- Show when user is logged in -->
                <span class="welcome-text">Welcome, <?php echo htmlspecialchars(getUserName()); ?></span>
                <a href="dashboard.php" class="btn-dashboard">Dashboard</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <!-- Show when user is not logged in -->
                <a href="register.php" class="cta">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
    </header>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <h2>Login to Your Account</h2>
            
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
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit">Login</button>
            </form>
            
            <div class="link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>