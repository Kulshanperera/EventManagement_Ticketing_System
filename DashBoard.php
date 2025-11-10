<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TicketBox Style Top Menu</title>

<link rel ="stylesheet" href ="HomePage.css">

</head>

<header>
  <nav class="navbar">
    <a href="#" class="logo">Event & Ticketing Box</a>
    <ul>
      <li><a href="HomePage.html">Home</a></li>
      <li><a href="#">Ticketing</a></li>
      <li><a href="#">Browse Events</a></li>
      <li><a href="#">About</a></li>
    </ul>
    <a href="Logout.php" class="cta">Logout</a>
  </nav>
</header>
<body class="back">

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Event & Ticketing Box. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
