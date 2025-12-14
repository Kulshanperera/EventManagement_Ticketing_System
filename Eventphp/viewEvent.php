<?php
require_once 'config.php';

$event_id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);

$ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
$tickets = mysqli_query($conn, $ticket_sql);

// // Determine back button URL based on user role
// function isLoggedIn() {
//     return isset($_SESSION['user_id']);
// }

// function isAdmin() {
//     return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
// }

$back_url = 'DashBoard.php';
if (isLoggedIn() && isAdmin()) {
    $back_url = 'allEventsAdmin.php';
}

// // Check if ID exists in URL
// if (!isset($_GET['id']) || empty($_GET['id'])) {
//     redirect('index.php');
// }

// $event_id = intval($_GET['id']); // Convert to integer for security

// $sql = "SELECT * FROM events WHERE id = $event_id";
// $result = mysqli_query($conn, $sql);

// // Check if query was successful and event exists
// if (!$result || mysqli_num_rows($result) == 0) {
//     redirect('index.php');
// }

// $event = mysqli_fetch_assoc($result);

// $ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
// $tickets = mysqli_query($conn, $ticket_sql);

// // Determine back button URL based on user role and referrer
// $back_url = 'index.php';
// $back_text = 'Events';

// if (isLoggedIn() && isAdmin()) {
//     // Check if admin came from dashboard or customer view
//     if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'index.php') !== false) {
//         $back_url = 'index.php';
//         $back_text = 'Events';
//     } else {
//         $back_url = 'admin_dashboard.php';
//         $back_text = 'Dashboard';
//     }
// } else {
//     // Customer always goes back to events page
//     $back_url = 'index.php';
//     $back_text = 'Events';
// }

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
    <a href="homePage.php" class="logo">Event Garden</a>
    <ul>
      <li><a href="homePage.php">Home</a></li>
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
                        <span class="ticket-name"><?php echo htmlspecialchars($ticket['ticket_name']); ?></span>
                        <span class="ticket-price"><?php echo number_format($ticket['price']); ?> LKR</span>
                    </div>
                    <div class="ticket-controls">
                        <div>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $index; ?>, -1, <?php echo $ticket['price']; ?>, <?php echo $ticket['quantity'] ? $ticket['quantity'] : 999; ?>)" <?php echo $ticket['status'] == 'sold-out' ? 'disabled' : ''; ?>>−</button>
                            <span class="quantity" id="qty-<?php echo $index; ?>">0</span>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $index; ?>, 1, <?php echo $ticket['price']; ?>, <?php echo $ticket['quantity'] ? $ticket['quantity'] : 999; ?>)" <?php echo $ticket['status'] == 'sold-out' ? 'disabled' : ''; ?>>+</button>
                        </div>
                        <div style="text-align: right;">
                            <span class="status-badge status-<?php echo $ticket['status']; ?>">
                                <?php echo $ticket['status'] == 'available' ? 'Available now' : 'Sold out'; ?>
                            </span>
                            <?php if ($ticket['quantity'] && $ticket['status'] == 'available'): ?>
                                <div style="font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 5px;">
                                    <span id="available-<?php echo $index; ?>"><?php echo $ticket['quantity']; ?></span> tickets left
                                </div>
                            <?php endif; ?>
                        </div>
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