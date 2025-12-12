<?php

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
            <a href="index.php" class="logo">Event Garden</a>
            <ul class="nav-menu">
                <li><a href="HomePage.html">Home</a></li>
                <li><a href="#">Ticketing</a></li>
                <li><a href="#">Browse Events</a></li>
                <li><a href="#">About</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="../Eventphp/Login.php" class="cta">Login</a>
                <a href="../Eventphp/register.php" class="cta">Register</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section (Homepage Content) -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Event Garden</h1>
            <p>Discover amazing events and book your tickets seamlessly</p>
            <div class="hero-buttons">
                <a href="../Eventphp/DashBoard.php" class="btn-primary">Browse Events</a>
                <a href="../Eventphp/register.php" class="btn-secondary">Get Started</a>
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