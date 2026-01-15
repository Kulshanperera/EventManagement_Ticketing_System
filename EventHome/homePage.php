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
<title>Event Garden Home</title>

<link rel="stylesheet" href="../Eventcss/homePage.css">
<link rel="stylesheet" href="../Eventcss/adminDashboard.css">

</head>
<body class="back">


    <header>
  <nav class="navbar">
    <a href="homePage.php" class="logo">Event Garden</a>
       <div class="user-info">
        <?php if (isLoggedIn()): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="homePage.php" class="browse-btn">Home</a>
            <a href="help.php" class="browse-btn">Help</a>
            <a href="EventUsers/logout.php" class="browse-btn">Logout</a>
        <?php endif; ?>
        </div>
    </nav>
</header>

    <!-- Hero Section (Homepage Content) -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Event Garden</h1>
            <p>Discover amazing events and book your tickets seamlessly</p>
            <div class="hero-buttons">
                <?php if (isLoggedIn()): ?>
                   <?php if (isAdmin()): ?>
                    <a href="EventUsers/adminDashboard.php" class="btn-primary">Browse Events</a>
                <?php else: ?>
                <a href="Events/dashBoard.php" class="btn-primary">Browse Events</a>
                <?php endif; ?>
                <?php else: ?>
                <a href="EventUsers/login.php" class="btn-primary">Browse Events</a>
                <a href="EventUsers/register.php" class="btn-secondary">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="features-content">
            <br>
            <br>
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
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>