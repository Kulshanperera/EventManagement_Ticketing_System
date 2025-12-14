<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TicketBox Style Top Menu</title>

<link rel ="stylesheet" href ="HomePage.css">
<link rel="stylesheet" href="../eventcss/HomePage.css">

</head>
<body class="back">
    <!-- Header Navigation -->
    <header>
        <nav class="navbar">
            <a href="homePage.php" class="logo">Event Garden</a>
<ul class="nav-menu">
    <li><a href="homePage.php">Home</a></li>
    
    <?php if (isLoggedIn()): ?>
        <?php if (isAdmin()): ?>
            <!-- Show admin-specific link -->
            <li><a href="allEventsAdmin.php">Browse Events (Admin)</a></li>
        <?php else: ?>
            <!-- Show customer-specific link -->
            <li><a href="Dashboard.php">Browse Events</a></li>
        <?php endif; ?>
    <?php endif; ?>
    
    <li><a href="#">About</a></li>
                    <div class="auth-buttons">
            <?php if (isLoggedIn()): ?>
                <!-- Show when user is logged in -->
                <span class="welcome-text">Welcome, <?php echo htmlspecialchars(isset($_SESSION['username']) ? $_SESSION['username'] : 'User'); ?></span>
                <a href="logout.php" class="cta">Logout</a>
            <?php else: ?>
                <!-- Show when user is not logged in -->
                <a href="login.php"  class="cta" >Login</a>
                <a href="register.php"   class="cta">Register</a>
            <?php endif; ?>
        </div>
</ul>
    </header>

    <!-- Hero Section (Homepage Content) -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Event Garden</h1>
            <p>Discover amazing events and book your tickets seamlessly</p>
            <div class="hero-buttons">
                <a href="DashBoard.php" class="btn-primary">Browse Events</a>
                <a href="register.php" class="btn-secondary">Get Started</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-content">
            <h2>Why Join Us?</h2>
            <div class="features-grid">
                <div class="feature">
                    <h3>🎫 Easy Ticketing</h3>
                    <p>Book tickets for your favorite events in just a few clicks</p>
                </div>
                <div class="feature">
                    <h3>📅 Event Discovery</h3>
                    <p>Find events that match your interests and preferences</p>
                </div>
                <div class="feature">
                    <h3>🔒 Secure Payments</h3>
                    <p>Your transactions are safe and protected with us</p>
                </div>
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