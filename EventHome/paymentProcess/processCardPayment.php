<?php
require_once '../Config/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('customer_dashboard.php');
}

// Get payment data
$cardholder_name = mysqli_real_escape_string($conn, $_POST['cardholder_name']);
$card_number = preg_replace('/\s+/', '', $_POST['card_number']);
$expiry_date = $_POST['expiry_date'];
$cvv = $_POST['cvv'];

// Validate card details
$errors = array();

if (strlen($card_number) != 16 || !ctype_digit($card_number)) {
    $errors[] = "Invalid card number";
}

if (!preg_match('/^\d{2}\/\d{2}$/', $expiry_date)) {
    $errors[] = "Invalid expiry date format";
}

if (strlen($cvv) < 3 || strlen($cvv) > 4 || !ctype_digit($cvv)) {
    $errors[] = "Invalid CVV";
}

// If validation fails, redirect back
if (!empty($errors)) {
    $_SESSION['payment_error'] = implode(', ', $errors);
    header("Location: payment.php");
    exit();
}

// Get booking data
$event_id = intval($_POST['event_id']);
$ticket_ids = $_POST['ticket_id'];
$quantities = $_POST['quantity'];
$prices = $_POST['price'];
$total_amount = floatval($_POST['total_amount']);

$user_id = $_SESSION['user_id'];
$booking_reference = 'BK' . strtoupper(uniqid());
$card_last4 = substr($card_number, -4);

// Process payment (sandbox - always succeeds)
mysqli_begin_transaction($conn);

try {
    // Check availability
    for ($i = 0; $i < count($ticket_ids); $i++) {
        $ticket_id = intval($ticket_ids[$i]);
        $quantity = intval($quantities[$i]);
        
        $check_sql = "SELECT ticket_name, quantity FROM tickets WHERE id = $ticket_id FOR UPDATE";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (!$check_result || mysqli_num_rows($check_result) == 0) {
            throw new Exception("Ticket ID $ticket_id not found");
        }
        
        $ticket = mysqli_fetch_assoc($check_result);
        
        if ($ticket['quantity'] !== null && $ticket['quantity'] < $quantity) {
            throw new Exception("Not enough tickets available for '" . $ticket['ticket_name'] . "'");
        }
    }
    
    // Create booking
    $booking_sql = "INSERT INTO bookings (user_id, event_id, booking_reference, total_amount, payment_status, cardholder_name, card_last4) 
                    VALUES ($user_id, $event_id, '$booking_reference', $total_amount, 'completed', '$cardholder_name', '$card_last4')";
    
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
        
        $update_sql = "UPDATE tickets SET quantity = quantity - $quantity WHERE id = $ticket_id AND quantity IS NOT NULL";
        mysqli_query($conn, $update_sql);
        
        // Mark as sold out if quantity = 0
        $soldout_sql = "UPDATE tickets SET status = 'sold-out' WHERE id = $ticket_id AND quantity = 0";
        mysqli_query($conn, $soldout_sql);
    }
    
    mysqli_commit($conn);
    header("Location: ../Events/booking.php?ref=$booking_reference");
    exit();
    
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['payment_error'] = $e->getMessage();
    header("Location: payment.php");
    exit();
}
?>
