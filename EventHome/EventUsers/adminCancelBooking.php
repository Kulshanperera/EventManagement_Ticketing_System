<?php
require_once '../Config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['ref'])) {
    redirect('adminBookings.php');
}

$booking_reference = mysqli_real_escape_string($conn, $_GET['ref']);

// Get booking details
$sql = "SELECT * FROM bookings WHERE booking_reference = '$booking_reference' AND booking_status = 'confirmed'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    redirect('adminBookings.php');
}

$booking = mysqli_fetch_assoc($result);

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Get all tickets for this booking
    $tickets_sql = "SELECT ticket_id, quantity FROM booking_tickets WHERE booking_id = {$booking['id']}";
    $tickets_result = mysqli_query($conn, $tickets_sql);
    
    // Return tickets to inventory
    while ($ticket = mysqli_fetch_assoc($tickets_result)) {
        $ticket_id = $ticket['ticket_id'];
        $quantity = $ticket['quantity'];
        
        // Add back to ticket quantity
        $update_sql = "UPDATE tickets SET quantity = quantity + $quantity WHERE id = $ticket_id AND quantity IS NOT NULL";
        mysqli_query($conn, $update_sql);
        
        // Check if ticket should be marked as available again
        $check_sql = "SELECT quantity, status FROM tickets WHERE id = $ticket_id";
        $check_result = mysqli_query($conn, $check_sql);
        $ticket_data = mysqli_fetch_assoc($check_result);
        
        if ($ticket_data['quantity'] > 0 && $ticket_data['status'] == 'sold-out') {
            $available_sql = "UPDATE tickets SET status = 'available' WHERE id = $ticket_id";
            mysqli_query($conn, $available_sql);
        }
    }
    
    // Mark booking as cancelled
    $cancel_sql = "UPDATE bookings SET booking_status = 'cancelled', cancelled_at = NOW() WHERE id = {$booking['id']}";
    
    if (!mysqli_query($conn, $cancel_sql)) {
        throw new Exception("Failed to cancel booking");
    }
    
    mysqli_commit($conn);
    redirect('adminBookings.php?msg=cancelled');
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    redirect('adminBookings.php?msg=error');
}
?>

-- admin_users.php
<?php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'deactivated') {
        $message = 'User deactivated successfully';
    } elseif ($_GET['msg'] == 'activated') {
        $message = 'User activated successfully';
    } elseif ($_GET['msg'] == 'deleted') {
        $message = 'User deleted successfully';
    }
}

// Get all users
$sql = "SELECT id, username, email, role, status, created_at FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
 <link rel="stylesheet" href="../../Eventcss/adminCancelBooking.css">
 <link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
 <body class="back">
    <nav class="navbar">
        <a href="../homePage.php" class="logo">Event Garden</a>
        <h1>User Management</h1>
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
        
        <?php
        // Calculate stats
        $total_users = mysqli_num_rows($result);
        $admin_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='admin'"));
        $customer_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='customer'"));
        $active_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE status='active'"));
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo $total_users; ?></div>
            </div>
            <div class="stat-card">
                <h3>Administrators</h3>
                <div class="number"><?php echo $admin_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Customers</h3>
                <div class="number"><?php echo $customer_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Users</h3>
                <div class="number"><?php echo $active_count; ?></div>
            </div>
        </div>
        
        <div class="table-container">
            <h2>All Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($result, 0); // Reset pointer
                    while ($user = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge-role badge-<?php echo $user['role']; ?>">
                                    <?php echo strtoupper($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status badge-<?php echo $user['status']; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <?php if ($user['status'] == 'active'): ?>
                                        <a href="admin_user_action.php?action=deactivate&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-deactivate" 
                                           onclick="return confirm('Deactivate this user?')">Deactivate</a>
                                    <?php else: ?>
                                        <a href="admin_user_action.php?action=activate&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-activate">Activate</a>
                                    <?php endif; ?>
                                    <a href="admin_user_action.php?action=delete&id=<?php echo $user['id']; ?>" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('Delete this user permanently?')">Delete</a>
                                <?php else: ?>
                                    <span style="color: #999;">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>