<!-- DEBUG: Event ID from URL = ? -->
<?php
require_once '../Config/config.php';

$event_id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);

$ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
$tickets = mysqli_query($conn, $ticket_sql);

$back_url = 'dashBoard.php';
if (isLoggedIn() && isAdmin()) {
    $back_url = '../EventUsers/adminDashboard.php';
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $event['title']; ?></title>
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
    <link rel="stylesheet" href="../../Eventcss/viewEvent.css">
    <!-- <script src="../EventJavascript/Event.js"><script> -->
   <script>
    var quantities = {};
    var prices = {};
    var maxQuantities = {};
    var ticketIds = {};
    var eventId = <?php echo intval($event_id); ?>; 
    
    function updateQty(index, change, price, maxQty, ticketId) {
        if (!quantities[index]) quantities[index] = 0;
        if (!prices[index]) prices[index] = price;
        if (!maxQuantities[index]) maxQuantities[index] = maxQty;
        if (!ticketIds[index]) ticketIds[index] = ticketId;
        
        var newQty = quantities[index] + change;
        
        if (newQty < 0) {
            return;
        }
        
        if (newQty > maxQuantities[index]) {
            var ticketName = document.querySelectorAll('.ticket-name')[index].textContent;
            alert('Sorry! Only ' + maxQuantities[index] + ' tickets available for ' + ticketName);
            return;
        }
        
        quantities[index] = newQty;
        document.getElementById('qty-' + index).textContent = quantities[index];
        
        var availableElement = document.getElementById('available-' + index);
        if (availableElement) {
            var remainingTickets = maxQuantities[index] - quantities[index];
            availableElement.textContent = remainingTickets;
        }
        
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
    
    function proceedToCheckout() {
        var totalTickets = 0;
        for (var key in quantities) {
            totalTickets += quantities[key];
        }
        
        if (totalTickets == 0) {
            alert('Please select at least one ticket!');
            return;
        }
        
        console.log('Event ID:', eventId);
        console.log('Quantities:', quantities);
        console.log('Ticket IDs:', ticketIds);
        
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '../paymentProcess/orderConfirmation.php';
        
        var eventInput = document.createElement('input');
        eventInput.type = 'hidden';
        eventInput.name = 'event_id';
        eventInput.value = eventId;
        form.appendChild(eventInput);
        
        for (var key in quantities) {
            if (quantities[key] > 0) {
                var idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ticket_id[]';
                idInput.value = ticketIds[key];
                form.appendChild(idInput);
                
                var qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'quantity[]';
                qtyInput.value = quantities[key];
                form.appendChild(qtyInput);
                
                var priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.name = 'price[]';
                priceInput.value = prices[key];
                form.appendChild(priceInput);
            }
        }
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
</head>
<header>
  <nav class="navbar">
    <a href="../homePage.php" class="logo">Event Garden</a>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?></span>
            <a href="../homePage.php" class="browse-btn">Home</a>            <?php if (isAdmin()): ?>
                <a href="../EventUsers/adminDashboard.php" class="browse-btn">Dashboard</a>
                <?php endif; ?>
            <a href="../help.php" class="browse-btn">About</a>
            <a href="../EventUsers/logout.php" class="logout">Logout</a>
        </div>
    </nav>
</header>
<body class="back">

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
                <p><h2>Event Info 🔊 </h2> 
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
            <?php 
            $ticket_index = 0;
            $ticket_result = mysqli_query($conn, $ticket_sql); // Re-query for loop
            while ($ticket = mysqli_fetch_assoc($ticket_result)): 
            ?>
                <div class="ticket-item">
                    <div class="ticket-header">
                        <span class="ticket-name"><?php echo htmlspecialchars($ticket['ticket_name']); ?></span>
                        <span class="ticket-price"><?php echo number_format($ticket['price']); ?> LKR</span>
                    </div>
                    <div class="ticket-controls">
                       <div>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $ticket_index; ?>, -1, <?php echo $ticket['price']; ?>, <?php echo $ticket['quantity'] ? $ticket['quantity'] : 999; ?>, <?php echo $ticket['id']; ?>)" <?php echo $ticket['status'] == 'sold-out' ? 'disabled' : ''; ?>>−</button>
                            <span class="quantity" id="qty-<?php echo $ticket_index; ?>">0</span>
                            <button class="quantity-btn" onclick="updateQty(<?php echo $ticket_index; ?>, 1, <?php echo $ticket['price']; ?>, <?php echo $ticket['quantity'] ? $ticket['quantity'] : 999; ?>, <?php echo $ticket['id']; ?>)" <?php echo $ticket['status'] == 'sold-out' ? 'disabled' : ''; ?>>+</button>
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
            <?php $ticket_index++; endwhile; ?>
            
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
    
            <button class="btn-checkout" onclick="proceedToCheckout()">Proceed to Checkout</button>
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