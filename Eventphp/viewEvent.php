<?php
require_once 'config.php';

$event_id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);

$ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
$tickets = mysqli_query($conn, $ticket_sql);

$back_url = 'DashBoard.php';
if (isLoggedIn() && isAdmin()) {
    $back_url = 'allEventsAdmin.php';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $event['title']; ?></title>
    <link rel="stylesheet" href="../eventcss/HomePage.css">
    <link rel="stylesheet" href="../eventcss/viewEvent.css">
    <script src="../EventJavascript/Event.js"></script>
</head>
<header>
  <nav class="navbar">
    <a href="#" class="logo">Event Garden</a>
    <ul>
      <li><a href="HomePage.html">Home</a></li>
      <li><a href="#">Ticketing</a></li>
      <li><a href="#">Browse Events</a></li>
      <li><a href="#">About</a></li>
      <?php if (isLoggedIn()): ?>
    <?php if (isAdmin()): ?>
      <li><a href="event.php">Create an event</a></li>
       <?php endif; ?>

       <?php else: ?>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
<?php endif; ?>
    </ul>
    <a href="Logout.php" class="cta">Logout</a>
  </nav>
</header>
<body class="back">
            <div class="nav">
            <a href="<?php echo $back_url; ?>" class="back-btn">← Back to <?php echo isLoggedIn() && isAdmin() ? 'Events' : 'Dashboard'; ?></a>
            </div>
    <div class="container">
        
        <div>
            <div class="event-section">
                <?php if ($event['image']): ?>
                    <img src="<?php echo $event['image']; ?>" class="event-image" alt="Event">
                <?php else: ?>
                    <img src="Eventimages/DefaultEvent.jpg" class="event-image" alt="Event">
                <?php endif; ?>
                
                <div class="event-info">
                    <span class="event-badge"><?php echo ucfirst($event['category']); ?></span>
                    <h1 class="event-title"><?php echo $event['title']; ?></h1>
                    <p class="event-meta">📅 <?php echo date('l, F d, Y', strtotime($event['event_date'])); ?></p>
                    <p class="event-meta">🕐 <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                    <p class="event-meta">📌<?php echo $event['location']; ?></p>
                </div>
            </div>
            
            <div class="event-details">
                <p><h2>Event Details 🔊 </h2> 
                <?php echo nl2br($event['description']); ?></p>
            </div>
            
                <?php if (!empty($event['summary'])): ?>
                 <div class="event-details">   
                <p><h2>Summary:</h2> 
                <?= $event['summary']; ?></p>
                    </div>
                    <?php endif; ?>
        
        </div>
        
        <div class="ticket-section">
            <h2>Select Tickets</h2>
            
            <?php $index = 0; while ($ticket = mysqli_fetch_assoc($tickets)): ?>
                <div class="ticket-item">
                    <div class="ticket-header">
                        <span class="ticket-name"><?php echo $ticket['ticket_name']; ?></span>
                        <span class="ticket-price"><?php echo number_format($ticket['price']); ?> LKR</span>
                    </div>
                    <div class="ticket-controls">
                        <div>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $index; ?>, -1, <?php echo $ticket['price']; ?>)">−</button>
                            <span class="quantity" id="qty-<?php echo $index; ?>">0</span>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $index; ?>, 1, <?php echo $ticket['price']; ?>)">+</button>
                        </div>
                        <span class="status-badge status-<?php echo $ticket['status']; ?>">
                            <?php echo $ticket['status'] == 'available' ? 'Available now' : 'Sold out'; ?>
                        </span>
                    </div>
                </div>
            <?php $index++; endwhile; ?>
            
            <div class="summary">
                <div class="summary-row">
                    <span>Total Tickets:</span>
                    <span id="totalTickets">0</span>
                </div>
                <div class="summary-row">
                    <strong>Total Price:</strong>
                    <strong id="totalPrice">0 LKR</strong>
                </div>
            </div>
            
            <button class="btn-checkout">Proceed to Checkout</button>
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