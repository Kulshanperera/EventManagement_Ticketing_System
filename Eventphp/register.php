<?php
require_once 'config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$errors = array();
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors[] = "Username or email already exists";
        }
        mysqli_stmt_close($stmt);
    }
    
    // Insert user if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Registration successful! You can now login.";
        } else {
            $errors[] = "Registration failed. Please try again.";
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
    <link rel="stylesheet" href="HomePage.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f4f4ff; 
            min-height: 100vh;
        }
        
        /* Register Form Styles */
        .register-section {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 40px 20px;
        }
        
        .register-container { 
            background: linear-gradient(135deg, #cda1fdff 0%, #6781f8ff 100%);
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
        }
        
        .register-container h2 { 
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
            border: 1px solid #cb0df1ff; 
            border-radius: 4px; 
            font-size: 14px; 
        }
        
        input:focus { 
            outline: none; 
            border-color: #4CAF50; 
        }
        
        button { 
            width: 100%; 
            padding: 12px; 
            background: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            font-weight: bold; 
        }
        
        button:hover { 
            background: #45a049; 
        }
        
        .error { 
            background: #f44336; 
            color: white; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
        }
        
        .success { 
            background: #4CAF50; 
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
            color: #4CAF50; 
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
            <a href="index.php" class="logo">Event Garden</a>
            <ul class="nav-menu">
                <li><a href="HomePage.html">Home</a></li>
                <li><a href="#">Ticketing</a></li>
                <li><a href="#">Browse Events</a></li>
                <li><a href="#">About</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="login.php" class="cta">Login</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section (Homepage Content) -->
    <!-- <section class="hero">
        <div class="hero-content">
            <h1>Join Event Garden</h1>
            <p>Create an account to discover amazing events and book tickets seamlessly</p>
            <div class="hero-buttons">
                <a href="events.php" class="btn-primary">Browse Events</a>
                <a href="#register" class="btn-secondary">Sign Up Now</a>
            </div>
        </div>
    </section> -->

    <!-- Register Section -->
    <section class="register-section" id="register">
        <div class="register-container">
            <h2>Create Your Account</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <button type="submit">Register</button>
            </form>
            
            <div class="link">
                Already have an account? <a href="login.php">Login here</a>
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