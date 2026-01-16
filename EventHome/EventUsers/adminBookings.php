<?php
require_once '../Config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'cancelled') {
        $message = 'Booking cancelled successfully. Tickets have been returned to inventory.';
    }
}

// Get all bookings with user and event details
$sql = "SELECT b.*, u.username, u.email, e.title as event_title, e.event_date, e.event_time 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN events e ON b.event_id = e.id 
        ORDER BY b.created_at DESC";
$result = mysqli_query($conn, $sql);

// Get statistics
$total_bookings = mysqli_num_rows($result);
$confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE booking_status='confirmed'"));
$cancelled = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM bookings WHERE booking_status='cancelled'"));
$revenue_result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM bookings WHERE booking_status='confirmed'"));
$total_revenue = isset($revenue_result['total']) ? $revenue_result['total'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="../../Eventcss/adminBookings.css">
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
 <body class="back">
    <nav class="navbar">
        <a href="../homePage.php" class="logo">Event Garden</a>
        <h1>Booking Management</h1>
        <div class="user-info">
            <span class="badge">ADMIN</span>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="adminDashboard.php">Dashboard</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>
    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="number"><?php echo $total_bookings; ?></div>
            </div>
            <div class="stat-card">
                <h3>Confirmed</h3>
                <div class="number" style="color: #28a745;"><?php echo $confirmed; ?></div>
            </div>
            <div class="stat-card">
                <h3>Cancelled</h3>
                <div class="number" style="color: #dc3545;"><?php echo $cancelled; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="number" style="color: #007bff;"><?php echo number_format($total_revenue); ?> LKR</div>
            </div>
        </div>
        
        <div class="table-container">
            <h2>All Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Customer</th>
                        <th>Event</th>
                        <th>Event Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($result, 0);
                    while ($booking = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr>
                            <td><strong><?php echo $booking['booking_reference']; ?></strong></td>
                            <td>
                                <?php echo htmlspecialchars($booking['username']); ?><br>
                                <small style="color: #666;"><?php echo htmlspecialchars($booking['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($booking['event_title']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['event_date'])); ?><br>
                                <small style="color: #666;"><?php echo date('g:i A', strtotime($booking['event_time'])); ?></small>
                            </td>
                            <td><strong><?php echo number_format($booking['total_amount']); ?> LKR</strong></td>
                            <td>
                                <span class="badge-status badge-<?php echo $booking['booking_status']; ?>">
                                    <?php echo ucfirst($booking['booking_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($booking['created_at'])); ?></td>
                            <td>
                                <a href="adminBookingDetails.php?ref=<?php echo $booking['booking_reference']; ?>" 
                                   class="btn btn-view">View Details</a>
                                <?php if ($booking['booking_status'] == 'confirmed'): ?>
                                    <a href="adminCancelBooking.php?ref=<?php echo $booking['booking_reference']; ?>" 
                                       class="btn btn-cancel" 
                                       onclick="return confirm('Cancel this booking and return tickets to inventory?')">Cancel Booking</a>
                                <?php else: ?>
                                    <button class="btn" disabled>Cancelled</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
        <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>