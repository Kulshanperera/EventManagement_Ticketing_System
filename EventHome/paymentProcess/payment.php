<?php
require_once '../Config/config.php';

if (!isLoggedIn() || isAdmin()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    redirect('customer_dashboard.php');
}

$event_id = intval($_POST['event_id']);
$ticket_ids = $_POST['ticket_id'];
$quantities = $_POST['quantity'];
$prices = $_POST['price'];
$total_amount = floatval($_POST['total_amount']);

// Get event details
$event_sql = "SELECT title FROM events WHERE id = $event_id";
$event_result = mysqli_query($conn, $event_sql);
$event = mysqli_fetch_assoc($event_result);
?>

<!DOCTYPE html>
<html>
<head><!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
<link rel="stylesheet" href="../../Eventcss/orderConfirmation.css">
<link rel="stylesheet" href="../../Eventcss/homePage.css">
<link rel="stylesheet" href="../../Eventcss/adminDashboard.css">
<link rel="stylesheet" href="../../Eventcss/payment.css">
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
            <a href="logout.php" class="logout" >Logout</a>
        </div>
  </nav>
</header>
<body class="back">
    <title>Payment</title>
</head>
<body>
    <div class="container">
      
        <div class="payment-card">
            <h1>Payment Details</h1>
            <p class="subtitle">Enter your card information to complete the purchase</p>
            
            <div class="amount-box">
                <div class="amount-label">Total Amount</div>
                <div class="amount-value"><?php echo number_format($total_amount); ?> LKR</div>
                <div style="color: #666; font-size: 14px; margin-top: 10px;">for <?php echo htmlspecialchars($event['title']); ?></div>
            </div>
                   
      
             <div class="card-icons">
              <img  src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png"> 
               <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"> 
            </div>
            
            <form id="paymentForm" method="POST" action="completePayment.php">
                <!-- Hidden fields -->
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <?php for ($i = 0; $i < count($ticket_ids); $i++): ?>
                    <input type="hidden" name="ticket_id[]" value="<?php echo $ticket_ids[$i]; ?>">
                    <input type="hidden" name="quantity[]" value="<?php echo $quantities[$i]; ?>">
                    <input type="hidden" name="price[]" value="<?php echo $prices[$i]; ?>">
                <?php endfor; ?>
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                
                <div id="errorMessage"></div>
                
                <div class="form-group">
                    <label>Cardholder Name *</label>
                    <input type="text" id="cardholderName" name="cardholder_name" required placeholder="Enter Your Name" maxlength="100">
                </div>
                
                <div class="form-group">
                    <label>Card Number *</label>
                    <input type="text" id="cardNumber" name="card_number" required placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                
                <div class="card-row">
                    <div class="form-group">
                        <label>Expiry Date (MM/YY) *</label>
                        <input type="text" id="expiryDate" name="expiry_date" required placeholder="12/26" maxlength="5">
                    </div>
                    
                    <div class="form-group">
                        <label>CVV *</label>
                        <input type="text" id="cvv" name="cvv" required placeholder="123" maxlength="4">
                    </div>
                </div>
                
                <button type="submit" id="payButton" class="btn-pay">Pay <?php echo number_format($total_amount); ?> LKR</button>
                <div class="secure-badge">Secure Payment - (Sandbox)</div>
            </form>
        </div>
    </div>
    
    <script>
        // Format card number with spaces
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
        
        // Format expiry date
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0, 2) + '/' + value.slice(2, 4);
            }
            e.target.value = value;
        });
        
        // Only allow numbers in CVV
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
        
        // Validate form before submit
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            let expiryDate = document.getElementById('expiryDate').value;
            let cvv = document.getElementById('cvv').value;
            let errorDiv = document.getElementById('errorMessage');
            
            errorDiv.innerHTML = '';
            
            // Validate card number (must be 16 digits)
            if (cardNumber.length !== 16 || !/^\d+$/.test(cardNumber)) {
                errorDiv.innerHTML = '<div class="error">Please enter a valid 16-digit card number</div>';
                return false;
            }
            
            // Validate expiry date
            if (!/^\d{2}\/\d{2}$/.test(expiryDate)) {
                errorDiv.innerHTML = '<div class="error">Please enter expiry date in MM/YY format</div>';
                return false;
            }
            
            let [month, year] = expiryDate.split('/');
            if (parseInt(month) < 1 || parseInt(month) > 12) {
                errorDiv.innerHTML = '<div class="error">Invalid expiry month (01-12)</div>';
                return false;
            }
            
            // Validate CVV
            if (cvv.length < 3 || cvv.length > 4 || !/^\d+$/.test(cvv)) {
                errorDiv.innerHTML = '<div class="error">Please enter a valid CVV (3-4 digits)</div>';
                return false;
            }
            
            // Disable button and submit
            document.getElementById('payButton').disabled = true;
            document.getElementById('payButton').textContent = 'Processing...';
            this.submit();
        });
    </script>

  <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>