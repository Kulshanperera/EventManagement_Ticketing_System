<?php
require_once '../Config/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'Eventimages/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image = $upload_dir . time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }
    
    // Insert event
    $sql = "INSERT INTO events (title, event_date, event_time, location, category, description, summary, image) 
            VALUES ('$title', '$event_date', '$event_time', '$location', '$category', '$description', '$summary', '$image')";
    
    if (mysqli_query($conn, $sql)) {
        $event_id = mysqli_insert_id($conn);
        
        // Insert tickets
        if (isset($_POST['ticket_name']) && is_array($_POST['ticket_name'])) {
            for ($i = 0; $i < count($_POST['ticket_name']); $i++) {
                $ticket_name = mysqli_real_escape_string($conn, $_POST['ticket_name'][$i]);
                $price = $_POST['ticket_price'][$i];
                $quantity = $_POST['ticket_quantity'][$i];
                $status = $_POST['ticket_status'][$i];
                
                $ticket_sql = "INSERT INTO tickets (event_id, ticket_name, price, quantity, status) 
                              VALUES ($event_id, '$ticket_name', $price, $quantity, '$status')";
                mysqli_query($conn, $ticket_sql);
            }
        }
        
        $message = "Event created successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Create Event</title>
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
    <link rel="stylesheet" href="../../Eventcss/event.css">
    <script src="../EventJavascript/Event.js"></script>
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
        
        <h1>Create New Event</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <!-- Event Information -->
            <div class="form-section">
                <h2>Event Information</h2>
                
                <div class="form-group">
                    <label>Event Title *</label>
                    <input type="text" name="title" id="title" >
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Event Date *</label>
                        <input type="date" name="event_date" id="eventDate">
                    </div>
                    
                    <div class="form-group">
                        <label>Event Time *</label>
                        <input type="time" name="event_time" id="eventTime" >
                    </div>
                </div>

                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" id="location">
                </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" id="category">
                        <option value="">Select category</option>
                        <option value="concert">Concert</option>
                        <option value="sports">Sports</option>
                        <option value="conference">Conference</option>
                        <option value="festival">Festival</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Event Image </label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <button type="button" class="clearButton" id="clearImage">clear</button>
                </div>
            </div> 
        </div>
            
            <!-- About Event -->
            <div class="form-section">
                <h2>About This Event</h2>
                
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" id="description"  maxlength="500"></textarea>
                    <div id="descCounter">0 / 500</div>
                </div>
                
                <div class="form-group">
                    <label>Summary</label>
                    <textarea name="summary" id="sum" maxlength="100"></textarea>
                    <div id="sumCounter">0 / 100</div>
                </div>
            </div>
            
            <!-- Tickets -->
            
            <div class="form-section">
                <h2>Ticket Pricing</h2>
                
                <div id="ticketContainer">
                    <div class="ticket-item">
                        <h3>Ticket 1</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ticket Name *</label>
                                <input type="text" name="ticket_name[]" required>
                            </div>
                            <div class="form-group">
                                <label>Price (LKR) *</label>
                                <input type="number" name="ticket_price[]" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="ticket_quantity[]">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="ticket_status[]">
                                    <option value="available">Available</option>
                                    <option value="sold-out">Sold Out</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn" onclick="addTicket()">+ Add Ticket</button>
            </div>
           
            <button type="submit" class="btn btn-submit">Create Event</button>
            <button type="reset" class="btn btn-submit">Reset Form</button>
        </form>
    </div>
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>&copy; 2026 Event Garden. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
