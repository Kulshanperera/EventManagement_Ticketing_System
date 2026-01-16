<?php
require_once '../Config/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../EventUsers/login.php');
}

if (!isset($_GET['ref'])) {
    redirect('dashBoard.php');
}

$booking_reference = mysqli_real_escape_string($conn, $_GET['ref']);

$sql = "SELECT b.*, e.title, e.event_date, e.event_time, e.location, u.email 
        FROM bookings b 
        JOIN events e ON b.event_id = e.id 
        JOIN users u ON b.user_id = u.id 
        WHERE b.booking_reference = '$booking_reference' AND b.user_id = {$_SESSION['user_id']}";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    redirect('customer_dashboard.php');
}

$booking = mysqli_fetch_assoc($result);

// Get booking tickets
$tickets_sql = "SELECT bt.*, t.ticket_name 
                FROM booking_tickets bt 
                JOIN tickets t ON bt.ticket_id = t.id 
                WHERE bt.booking_id = {$booking['id']}";
$tickets = mysqli_query($conn, $tickets_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmed <?php echo $booking['booking_reference']; ?></title>
    <link rel="stylesheet" href="../../Eventcss/booking.css">
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
    <script src="../EventJavascript/Event.js"></script>

</head>
<header>
  <nav class="navbar">
    <a href="../homePage.php" class="logo">Event Garden</a>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../homePage.php" class="browse-btn">Home</a>
            <?php if (isAdmin()): ?>
                <a href="../EventsUsers/adminDashboard.php" class="browse-btn">Dashboard</a>
                <?php endif; ?>
            <a href="../help.php" class="browse-btn">Help</a>
            <a href="../EventUsers/logout.php" class="logout">Logout</a>
        </div>
  </nav>
</header>
<body class="back">
    <div class="container">
        <div class="success-card">
            <button onclick="window.print()" id="printBtn" class="btn-dashboard">Print</button>
            <div class="success-icon">✅</div>
            <h1>Booking Confirmed!</h1>
            <p style="color: #666; font-size: 16px;">Your tickets have been successfully booked</p>
            
            <div class="ref-number">
                Booking Reference: <?php echo $booking['booking_reference']; ?>
            </div>
            
            <div class="ticket-section">
                <h2>Booking Details</h2>
                
                <div class="event-details">
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($booking['title']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($booking['event_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($booking['event_time'])); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($booking['location']); ?></p>
                </div>
                
                <h3 style="color: #555; margin-bottom: 10px;">Tickets</h3>
                <?php while ($ticket = mysqli_fetch_assoc($tickets)): ?>
                    <div class="ticket-item">
                        <div>
                            <div style="font-weight: bold; color: #333;"><?php echo htmlspecialchars($ticket['ticket_name']); ?></div>
                            <div style="color: #666; font-size: 14px;"><?php echo $ticket['quantity']; ?> × <?php echo number_format($ticket['price']); ?> LKR</div>
                        </div>
                        <div style="font-weight: bold; color: #333;"><?php echo number_format($ticket['quantity'] * $ticket['price']); ?> LKR</div>
                    </div>
                <?php endwhile; ?>
                
                <div class="total-row">
                    <span>Total Paid:</span>
                    <span><?php echo number_format($booking['total_amount']); ?> LKR</span>
                </div>
            </div>
            
            <div class="email-notice">
                📧 A confirmation email has been sent to <?php echo htmlspecialchars($booking['email']); ?>
            </div>
            
            <a href="dashBoard.php" class="btn-dashboard">Back to Dashboard</a>
        </div>
    </div>
      <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
