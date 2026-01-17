<?php
require_once '../Config/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isAdmin()) {
    redirect('adminDashboard.php');
}

$message = '';
if (isset($_GET['deleted'])) {
    $message = "Event deleted successfully!";
} elseif (isset($_GET['error'])) {
    $message = "Error deleting event!";
}

$sql = "SELECT * FROM events ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
<link rel="stylesheet" href="../../Eventcss/adminDashboard.css">
<link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
<header>
    <nav class="navbar">
         <a href="../homePage.php" class="logo">Event Garden</a>
        <h1>Admin Dashboard</h1>
        <div class="user-info">
            <span class="badge">ADMIN</span>
            <span>Welcome, <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?></span>
            <a href="../homePage.php" class="browse-btn">Home</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>
</header>
  <body class="back">

    <div class="container">
        <div class="nav">
            <a href="../Events/event.php">+ Create New Event</a>
            <a href="adminUsers.php">👥 Manage Users</a>
            <a href="adminBookings.php">📋 Manage Bookings</a>
            <a href="../Events/dashBoard.php">View as Customer</a>
        </div>
        
        <h2>Manage Events</h2>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="event-grid">
                <?php while ($event = mysqli_fetch_assoc($result)): ?>
                    <div class="event-card">
                        
                        <div class="event-content">
                            <h2 class="event-title"><?php echo $event['title']; ?></h2>
                            <p class="event-info">📅 <?php echo date('F d, Y', strtotime($event['event_date'])); ?></p>
                            <p class="event-info">🕐 <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                            <p class="event-info">📍 <?php echo $event['location']; ?></p>
                            <a href="../Events/viewEvent.php?id=<?php echo $event['id']; ?>" class="btn-view">View</a>
                            <a href="../Events/editEvent.php?id=<?php echo $event['id']; ?>" class="btn-edit">Edit</a>
                            <button class="btn-delete" onclick="deleteEvent(<?php echo $event['id']; ?>, '<?php echo addslashes($event['title']); ?>')">Delete</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-events">
                <p>📅 No events created yet</p>
                <a href="../Events/event.php" class="btn-create">Create Your First Event</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function deleteEvent(id, title) {
            if (confirm('Are you sure you want to delete "' + title + '"?')) {
                window.location.href = '../Events/deleteEvent.php?id=' + id;
            }
        }
    </script>

        <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>