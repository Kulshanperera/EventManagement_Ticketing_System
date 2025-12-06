<?php
require_once 'config.php';

$event_id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);

$ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
$tickets = mysqli_query($conn, $ticket_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $event['title']; ?></title>
    <link rel="stylesheet" href="../eventcss/HomePage.css">
    <link rel="stylesheet" href="../eventcss/viewEvent.css">

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
    <a href="allEvents.php" class="btn-back">← Back to Events</a>
    
    <div class="container">
        <div>
            <div class="event-section">
                <?php if ($event['image']): ?>
                    <img src="<?php echo $event['image']; ?>" class="event-image" alt="Event">
                <?php else: ?>
                    <img src="https://via.placeholder.com/800x400?text=Event" class="event-image" alt="Event">
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
                <h2>Event Details 🔊 </h2>
                <p><?php echo nl2br($event['description']); ?></p>
            </div>
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
    
    <script>
        var quantities = {};
        var prices = {};
        
        function updateQty(index, change, price) {
            if (!quantities[index]) quantities[index] = 0;
            if (!prices[index]) prices[index] = price;
            
            quantities[index] = Math.max(0, quantities[index] + change);
            document.getElementById('qty-' + index).textContent = quantities[index];
            
            updateTotal();
        }
        
        function updateTotal() {
            var totalTickets = 0;
            var totalPrice = 0;
            
            for (var key in quantities) {
                totalTickets += quantities[key];
                totalPrice += quantities[key] * prices[key];
            }
            
            document.getElementById('totalTickets').textContent = totalTickets;
            document.getElementById('totalPrice').textContent = totalPrice.toLocaleString() + ' LKR';
        }
    </script>

        <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 Event Garden. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>