<?php
require_once 'config.php';

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