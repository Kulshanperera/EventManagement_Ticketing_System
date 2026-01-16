<?php
require_once '../Config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}
$sql = "SELECT * FROM events ORDER BY event_date DESC";
$result = mysqli_query($conn, $sql);
$username = $_SESSION['username'];
$email = $_SESSION['email'];

$message = '';
if (isset($_GET['deleted'])) {
    $message = "";
} elseif (isset($_GET['error'])) {
    $message = "Error deleting event!";
}
$today = date('Y-m-d');

$sql = "SELECT * FROM events 
        WHERE event_date >= '$today'
        ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);

// COUNT upcoming events
$event_count = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Events</title>

<link rel="stylesheet" href="../../Eventcss/homePage.css">
    <link rel="stylesheet" href="../../Eventcss/allEvents.css">
    <link rel="stylesheet" href="../../Eventcss/adminDashboard.css">
    <script src="../../EventJavascript/Event.js"></script>
</head>
<header>
  <nav class="navbar">
    <a href="../homePage.php" class="logo">Event Garden</a>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../homePage.php" class="browse-btn">Home</a>
                        <?php if (isAdmin()): ?>
                <a href="../EventUsers/adminDashboard.php" class="browse-btn">Dashboard</a>
                <?php endif; ?>
            <a href="../help.php" class="browse-btn">Help</a>
            <a href="../EventUsers/logout.php" class="logout">Logout</a>
        </div>
  </nav>
</header>
<body class="back">
    <div class="container">
        <div class="nav">
        </div>
        <h1>Upcoming Events</h1>
        <h3>Total Events: <?php echo $event_count; ?></h3>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="event-grid">
            <?php while ($event = mysqli_fetch_assoc($result)): ?>
                <a href="viewEvent.php?id=<?php echo $event['id']; ?>" >
                <div class="event-card">
                    <?php if ($event['image']): ?>
                        <img src="<?php echo $event['image']; ?>" alt="Event Image">
                    <?php else: ?>
                        <img src="Eventimages/DefaultEvent.jpg" alt="DefualtEvent Image">
                    <?php endif; ?>
                    
                    <div class="event-content">
                        <h1 class="event-title"><?php echo $event['title']; ?></h1>
                        <p class="event-info">📅 <?php echo date('F d, Y', strtotime($event['event_date'])); ?></p>
                        <p class="event-info">🕐 <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p class="event-info">📍 <?php echo $event['location']; ?></p>
                        <p class="event-info">🖊 <?php $desc = $event['summary']; 
                        echo strlen($desc) > 100 ? substr($desc, 0, 100) . "..." : $desc;?></p>
                        
                    </div>
                </div>
                </a>
            <?php endwhile; ?>
            <?php else: ?>
             <div class="no-events">
                <p>📅 No events available at the moment</p>
                <a href="event.php" class="btn-create">Create Your First Event</a>
             </div>
            <?php endif; ?>
        </div>
    </div>    
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
