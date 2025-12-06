<?php
require_once 'config.php';

$sql = "SELECT * FROM events ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Events List</title>
    <link rel="stylesheet" href="../eventcss/HomePage.css">
    <link rel="stylesheet" href="../eventcss/allEvents.css">
</head>
<header>
  <nav class="navbar">
    <a href="#" class="logo">Event Garden</a>
    <ul>
      <li><a href="HomePage.html">Home</a></li>
      <li><a href="#">Ticketing</a></li>
      <li><a href="#">Browse Events</a></li>
      <li><a href="#">About</a></li>
      <li><a href="event.php">Create an event</a></li>
    </ul>
    <a href="Logout.php" class="cta">Logout</a>
  </nav>
</header>
<body class="back">
    <div class="container">
        <div class="nav">
        </div>
        
        <h1>Upcoming Events</h1>
        
        <div class="event-grid">
            <?php while ($event = mysqli_fetch_assoc($result)): ?>
                <div class="event-card">
                    <?php if ($event['image']): ?>
                        <img src="<?php echo $event['image']; ?>" alt="Event Image">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/400x200?text=Event" alt="Event Image">
                    <?php endif; ?>
                    
                    <div class="event-content">
                        <h2 class="event-title"><?php echo $event['title']; ?></h2>
                        <p class="event-info">📅 <?php echo date('F d, Y', strtotime($event['event_date'])); ?></p>
                        <p class="event-info">🕐 <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                        <p class="event-info">📍 <?php echo $event['location']; ?></p>
                        <p class="event-info">🖊 <?php echo $event['description']; ?></p>
                        <a href="viewEvent.php?id=<?php echo $event['id']; ?>" class="btn-view">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>