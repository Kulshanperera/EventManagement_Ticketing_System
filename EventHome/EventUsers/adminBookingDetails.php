<?php
require_once '../Config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['ref'])) {
    redirect('adminBookings.php');
}

$booking_reference = mysqli_real_escape_string($conn, $_GET['ref']);

$sql = "SELECT b.*, u.username, u.email, e.title, e.event_date, e.event_time, e.location 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN events e ON b.event_id = e.id 
        WHERE b.booking_reference = '$booking_reference'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    redirect('adminBookings.php');
}

$booking = mysqli_fetch_assoc($result);

$tickets_sql = "SELECT bt.*, t.ticket_name 
                FROM booking_tickets bt 
                JOIN tickets t ON bt.ticket_id = t.id 
                WHERE bt.booking_id = {$booking['id']}";
$tickets = mysqli_query($conn, $tickets_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>
<link rel="stylesheet" href="../../Eventcss/adminBookingDetails.css">
<link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
<header>
    <nav class="navbar">
        <a href="../homePage.php" class="logo">Event Garden</a>
        <div class="user-info">
            <span class="badge">ADMIN</span>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="adminDashboard.php">Dashboard</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>
</header>
 <body class="back">
    <div class="container">
        <a href="adminBookings.php" class="back-btn">← Back to Bookings</a>
        
        <div class="details-card">
            <h1>Booking Details</h1>
            
            <span class="status-badge status-<?php echo $booking['booking_status']; ?>">
                <?php echo strtoupper($booking['booking_status']); ?>
            </span>
            
            <div class="info-section">
                <h2>Booking Information</h2>
                <div class="info-row">
                    <div class="info-label">Booking Reference:</div>
                    <div class="info-value"><?php echo $booking['booking_reference']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Booking Date:</div>
                    <div class="info-value"><?php echo date('F d, Y H:i', strtotime($booking['created_at'])); ?></div>
                </div>
                <?php if ($booking['booking_status'] == 'cancelled'): ?>
                <div class="info-row">
                    <div class="info-label">Cancelled On:</div>
                    <div class="info-value"><?php echo date('F d, Y H:i', strtotime($booking['cancelled_at'])); ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="info-section">
                <h2>Customer Information</h2>
                <div class="info-row">
                    <div class="info-label">Name:</div>
                    <div class="info-value"><?php echo htmlspecialchars($booking['username']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo htmlspecialchars($booking['email']); ?></div>
                </div>
            </div>
            
            <div class="info-section">
                <h2>Event Information</h2>
                <div class="info-row">
                    <div class="info-label">Event:</div>
                    <div class="info-value"><?php echo htmlspecialchars($booking['title']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date & Time:</div>
                    <div class="info-value">
                        <?php echo date('F d, Y', strtotime($booking['event_date'])); ?> at 
                        <?php echo date('g:i A', strtotime($booking['event_time'])); ?>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Location:</div>
                    <div class="info-value"><?php echo htmlspecialchars($booking['location']); ?></div>
                </div>
            </div>
            
            <div class="info-section">
                <h2>Tickets</h2>
                <?php while ($ticket = mysqli_fetch_assoc($tickets)): ?>
                    <div class="ticket-item">
                        <div>
                            <strong><?php echo htmlspecialchars($ticket['ticket_name']); ?></strong><br>
                            <small>Quantity: <?php echo $ticket['quantity']; ?> × <?php echo number_format($ticket['price']); ?> LKR</small>
                        </div>
                        <div><strong><?php echo number_format($ticket['quantity'] * $ticket['price']); ?> LKR</strong></div>
                    </div>
                <?php endwhile; ?>
                
                <div class="total-row">
                    <span>Total Amount:</span>
                    <span><?php echo number_format($booking['total_amount']); ?> LKR</span>
                </div>
            </div>
            
            <?php if ($booking['booking_status'] == 'confirmed'): ?>
                <a href="admin_cancel_booking.php?ref=<?php echo $booking['booking_reference']; ?>" 
                   class="btn-cancel" 
                   onclick="return confirm('Cancel this booking and return tickets to inventory?')">
                    Cancel This Booking
                </a>
            <?php endif; ?>
        </div>
    </div>
        <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>