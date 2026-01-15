<?php
require_once '../Config/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('../EventUsers/login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('../Events/dashBoard.php');
}

$event_id = intval($_POST['event_id']);
$ticket_ids = $_POST['ticket_id'];
$quantities = $_POST['quantity'];
$prices = $_POST['price'];
$total_amount = floatval($_POST['total_amount']);

$user_id = $_SESSION['user_id'];
$booking_reference = 'BK' . strtoupper(uniqid());

mysqli_begin_transaction($conn);

try {
    // Check availability
    for ($i = 0; $i < count($ticket_ids); $i++) {
        $ticket_id = intval($ticket_ids[$i]);
        $quantity = intval($quantities[$i]);
        
        $check_sql = "SELECT quantity FROM tickets WHERE id = $ticket_id FOR UPDATE";
        $check_result = mysqli_query($conn, $check_sql);
        $ticket = mysqli_fetch_assoc($check_result);
        
        if ($ticket['quantity'] < $quantity) {
            throw new Exception("Not enough tickets available");
        }
    }
    
    // Create booking
    $booking_sql = "INSERT INTO bookings (user_id, event_id, booking_reference, total_amount, payment_status, cardholder_name) 
                    VALUES ($user_id, $event_id, '$booking_reference', $total_amount, 'completed', '{$_SESSION['username']}')";
    
    if (!mysqli_query($conn, $booking_sql)) {
        throw new Exception("Failed to create booking");
    }
    
    $booking_id = mysqli_insert_id($conn);
    
    // Insert tickets and reduce quantity
    for ($i = 0; $i < count($ticket_ids); $i++) {
        $ticket_id = intval($ticket_ids[$i]);
        $quantity = intval($quantities[$i]);
        $price = floatval($prices[$i]);
        
        $bt_sql = "INSERT INTO booking_tickets (booking_id, ticket_id, quantity, price) 
                   VALUES ($booking_id, $ticket_id, $quantity, $price)";
        mysqli_query($conn, $bt_sql);
        
        $update_sql = "UPDATE tickets SET quantity = quantity - $quantity WHERE id = $ticket_id";
        mysqli_query($conn, $update_sql);
    }
    
    $soldout_sql = "UPDATE tickets SET status = 'sold-out' WHERE id = $ticket_id AND quantity = 0";
    mysqli_query($conn, $soldout_sql);

    mysqli_commit($conn);
    header("Location: ../Events/booking.php?ref=$booking_reference");
    exit();
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<h3>Payment Failed</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<a href='../Events/dashBoard.php'>Back to Dashboard</a>";
    exit();
}
?>