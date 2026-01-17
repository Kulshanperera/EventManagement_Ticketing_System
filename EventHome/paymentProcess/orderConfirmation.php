<?php
require_once '../Config/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../EventUsers/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../Events/dashBoard.php');
}

// Check POST data
if (!isset($_POST['event_id'])) {
    echo "<h3>Error: No event_id received</h3>";
    echo "<a href='../Events/dashBoard.php'>Back to Dashboard</a>";
    exit();
}

if (!isset($_POST['ticket_id']) || !is_array($_POST['ticket_id'])) {
    echo "<h3>Error: No ticket data received</h3>";
    echo "<a href='../Events/dashBoard.php'>Back to Dashboard</a>";
    exit();
}

$event_id = intval($_POST['event_id']);
$ticket_ids = $_POST['ticket_id'];
$quantities = $_POST['quantity'];
$prices = $_POST['price'];

// Get event details
$event_sql = "SELECT * FROM events WHERE id = $event_id";
$event_result = mysqli_query($conn, $event_sql);

if (!$event_result) {
    echo "<h3>Database Error</h3>";
    echo "<p>" . mysqli_error($conn) . "</p>";
    exit();
}

if (mysqli_num_rows($event_result) == 0) {
    echo "<h3>Error: Event not found</h3>";
    echo "<p>Event ID: $event_id</p>";
    echo "<a href='../Events/dashBoard.php'>Back to Dashboard</a>";
    exit();
}

$event = mysqli_fetch_assoc($event_result);

// Calculate total and get ticket details
$total_amount = 0;
$order_items = array();

for ($i = 0; $i < count($ticket_ids); $i++) {
    $ticket_id = intval($ticket_ids[$i]);
    $quantity = intval($quantities[$i]);
    $price = floatval($prices[$i]);
    
    $ticket_sql = "SELECT * FROM tickets WHERE id = $ticket_id";
    $ticket_result = mysqli_query($conn, $ticket_sql);
    
    if ($ticket_result && mysqli_num_rows($ticket_result) > 0) {
        $ticket = mysqli_fetch_assoc($ticket_result);
        
        $subtotal = $quantity * $price;
        $total_amount += $subtotal;
        
        $order_items[] = array(
            'ticket_id' => $ticket_id,
            'name' => $ticket['ticket_name'],
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal
        );
    }
}

if (count($order_items) == 0) {
    echo "<h3>Error: No valid tickets found</h3>";
    echo "<a href='../Events/dashBoard.php'>Back to Dashboard</a>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
<link rel="stylesheet" href="../../Eventcss/orderConfirmation.css">
<link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
<header>
  <nav class="navbar">
    <a href="../homePage.php" class="logo">Event Garden</a>
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?></span>
            <a href="../homePage.php" class="browse-btn">Home</a>            <?php if (isAdmin()): ?>
                <a href="../EventUsers/adminDashboard.php">Dashboard</a>
                <?php endif; ?>
            <a href="help.php" class="browse-btn">Help</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
  </nav>
</header>
<body class="back">

    <div class="container">
        <a href="../Events/viewEvent.php?id=<?php echo $event_id; ?>" class="back-btn">← Back to Event</a>
        
        <div class="confirmation-card">
            <h1>Order Confirmation</h1>
            <p class="subtitle">Please review your order before completing the payment</p>
            
            <div class="customer-info">
                <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?></p>
            </div>
            
            <div class="event-section">
                <h2>Event Details</h2>
                <div class="event-info">
                    <p><strong>Event:</strong> <?php echo htmlspecialchars($event['title']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($event['event_date'])); ?></p>
                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
                </div>
            </div>
            
            <div class="order-section">
                <h2>Order Summary</h2>
                <?php foreach ($order_items as $item): ?>
                    <div class="ticket-row">
                        <div class="ticket-details">
                            <div class="ticket-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="ticket-qty"><?php echo $item['quantity']; ?> ticket(s) × <?php echo number_format($item['price']); ?> LKR</div>
                        </div>
                        <div class="ticket-price"><?php echo number_format($item['subtotal']); ?> LKR</div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="total-section">
                <div class="total-row">
                    <span>Total Amount:</span>
                    <span><?php echo number_format($total_amount); ?> LKR</span>
                </div>
            </div>
            
            <form method="POST" action="../paymentProcess/payment.php">
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <?php for ($i = 0; $i < count($order_items); $i++): ?>
                    <input type="hidden" name="ticket_id[]" value="<?php echo $order_items[$i]['ticket_id']; ?>">
                    <input type="hidden" name="quantity[]" value="<?php echo $order_items[$i]['quantity']; ?>">
                    <input type="hidden" name="price[]" value="<?php echo $order_items[$i]['price']; ?>">
                <?php endfor; ?>
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                
                <button type="submit" class="btn-complete">Complete Payment</button>
            </form>
        </div>
    </div>

      <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
