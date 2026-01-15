<?php
require_once '../Config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Event Garden Home</title>

<link rel="stylesheet" href="../../Eventcss/homePage.css">
<link rel="stylesheet" href="../../Eventcss/adminDashboard.css">

</head>
<body class="back">
  <nav class="navbar">
    <a href="homePage.php" class="logo">Event Garden</a>
       <div class="user-info">
            <a href="homePage.php" class="browse-btn">Home</a>
            <a href="help.php" class="browse-btn">About</a>
        </div>
    </nav>
<?php
// Generate password hash
$password = 'admin123';
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Delete existing admin if any
mysqli_query($conn, "DELETE FROM users WHERE username = 'admin'");

// Insert new admin
$sql = "INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@user.com', '$hashed', 'admin')";

if (mysqli_query($conn, $sql)) {
    echo "Admin user created successfully!<br>";
    echo "Username: admin OR Email: admin@user.com<br>";
    echo "Password: admin123<br>";
    echo "Hash: $hashed";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>