<?php
require_once 'config.php';

$event_id = $_GET['id'];
$message = '';
$error = '';

// Fetch event data
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = mysqli_query($conn, $sql);
$event = mysqli_fetch_assoc($result);

// Fetch tickets
$ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
$tickets_result = mysqli_query($conn, $ticket_sql);
$tickets = array();
while ($ticket = mysqli_fetch_assoc($tickets_result)) {
    $tickets[] = $ticket;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = $_POST['category'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    
    $image = $event['image'];
    
    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'Eventimages/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Delete old image
        if ($image && file_exists($image)) {
            unlink($image);
        }
        
        $image = $upload_dir . time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }
    
    // Update event
    $update_sql = "UPDATE events SET 
                   title = '$title',
                   event_date = '$event_date',
                   event_time = '$event_time',
                   location = '$location',
                   category = '$category',
                   description = '$description',
                   summary = '$summary',
                   image = '$image'
                   WHERE id = $event_id";
    
    if (mysqli_query($conn, $update_sql)) {
        // Delete all existing tickets
        mysqli_query($conn, "DELETE FROM tickets WHERE event_id = $event_id");
        
        // Insert updated tickets
        if (isset($_POST['ticket_name']) && is_array($_POST['ticket_name'])) {
            for ($i = 0; $i < count($_POST['ticket_name']); $i++) {
                $ticket_name = mysqli_real_escape_string($conn, $_POST['ticket_name'][$i]);
                $price = floatval($_POST['ticket_price'][$i]); // Convert to float
                $quantity = intval($_POST['ticket_quantity'][$i]); // Convert to int
                $status = mysqli_real_escape_string($conn, $_POST['ticket_status'][$i]); // Escape status too
                
                $ticket_sql = "INSERT INTO tickets (event_id, ticket_name, price, quantity, status) 
                              VALUES ($event_id, '$ticket_name', $price, $quantity, '$status')";
                
                if (!mysqli_query($conn, $ticket_sql)) {
                    $error .= "Error inserting ticket: " . mysqli_error($conn) . "<br>";
                }
            }
        }
        
        $message = "Event updated successfully!";
        
        // Refresh event data
        $result = mysqli_query($conn, $sql);
        
        if (!$result) {
            die("Query Error 1: " . mysqli_error($conn));
        }
        
        $event = mysqli_fetch_assoc($result);
        
        // Refresh tickets data - FIXED: Use fresh query
        $ticket_sql = "SELECT * FROM tickets WHERE event_id = $event_id";
        $tickets_result = mysqli_query($conn, $ticket_sql);
        
        if (!$tickets_result) {
            die("Query Error 2: " . mysqli_error($conn));
        }
        
        $tickets = array();
        while ($ticket = mysqli_fetch_assoc($tickets_result)) {
            $tickets[] = $ticket;
        }
    } else {
        $error = "Error updating event: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Create Event</title>
    <link rel="stylesheet" href="../eventcss/HomePage.css">
    <link rel="stylesheet" href="../eventcss/Event.css">
    <script src="../EventJavascript/Event.js"></script>
        <title>Edit Event</title>

</head>
<header>
  <nav class="navbar">
    <a href="homePage.php" class="logo">Event Garden</a>
    <ul>
      <li><a href="homePage.php">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="event.php">Create an event</a></li>
    </ul>
    <a href="Logout.php" class="cta">Logout</a>
  </nav>
</header>
<body class="back">
    <div class="container">
        <div class="nav">
            <a href="allEventsAdmin.php">← Back to Events</a>
        </div>
        
        <h1>Edit Event</h1>
        
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
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($event['title']); ?>" >
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Event Date *</label>
                        <input type="date" name="event_date" id="eventDate" value="<?php echo $event['event_date']; ?>" >
                    </div>
                    
                    <div class="form-group">
                        <label>Event Time *</label>
                        <input type="time" name="event_time" id="event_time" value="<?php echo $event['event_time']; ?>" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($event['location']); ?>" >
                </div>
                
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category"  id="category" >
                        <option value="">Select category</option>
                        <option value="concert" <?php echo $event['category'] == 'concert' ? 'selected' : ''; ?>>Concert</option>
                        <option value="sports" <?php echo $event['category'] == 'sports' ? 'selected' : ''; ?>>Sports</option>
                        <option value="conference" <?php echo $event['category'] == 'conference' ? 'selected' : ''; ?>>Conference</option>
                        <option value="festival" <?php echo $event['category'] == 'festival' ? 'selected' : ''; ?>>Festival</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <?php if ($event['image']): ?>
                        <img src="<?php echo $event['image']; ?>" class="current-image" alt="Current Event Image">

                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>Upload New Image (Leave empty to keep current)</label>
                    <input type="file" name="image" accept="image/*">
                    <button type="button" class="clearButton" id="clearImage">clear</button>
                </div>
            </div>
            
            <!-- About Event -->
            <div class="form-section">
                <h2>About This Event</h2>
                
                <div class="form-group">
                    <label>Description *</label>
                    <textarea name="description" id="description" maxlength="500"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    <div id="descCounter">0 / 500</div>
                </div>
                
                <div class="form-group">
                    <label>Summary</label>
                    <textarea name="summary" id="sum" maxlength="100"><?php echo htmlspecialchars($event['summary']); ?></textarea>
                    <div id="sumCounter">0 / 100</div>
                </div>
            </div>
            
            <!-- Tickets -->
            <div class="form-section">
                <h2>Ticket Pricing</h2>
                
                <div id="ticketContainer">
                    <?php foreach ($tickets as $index => $ticket): ?>
                        <div class="ticket-item">
                            <h3>Ticket <?php echo $index + 1; ?> 
                                <?php if ($index > 0): ?>
                                    <button type="button" class="btn btn-remove" onclick="this.parentElement.parentElement.remove()">Remove</button>
                                <?php endif; ?>
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Ticket Name *</label>
                                    <input type="text" name="ticket_name[]" value="<?php echo htmlspecialchars($ticket['ticket_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Price (LKR) *</label>
                                    <input type="number" name="ticket_price[]" value="<?php echo $ticket['price']; ?>" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="ticket_quantity[]" value="<?php echo $ticket['quantity']; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="ticket_status[]">
                                        <option value="available" <?php echo $ticket['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                        <option value="sold-out" <?php echo $ticket['status'] == 'sold-out' ? 'selected' : ''; ?>>Sold Out</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" class="btn" onclick="addTicket()">+ Add Ticket</button>
            </div>
            <button type="submit" class="btn btn-submit">Update Event</button>
        </form>
    </div>
    
    <script>
        var ticketCount = <?php echo count($tickets); ?>;
        
        function addTicket() {
            ticketCount++;
            var html = '<div class="ticket-item">' +
                '<h3>Ticket ' + ticketCount + ' <button type="button" class="btn btn-remove" onclick="this.parentElement.parentElement.remove()">Remove</button></h3>' +
                '<div class="form-row">' +
                '<div class="form-group"><label>Ticket Name *</label><input type="text" name="ticket_name[]" required></div>' +
                '<div class="form-group"><label>Price (LKR) *</label><input type="number" name="ticket_price[]" required></div>' +
                '</div>' +
                '<div class="form-row">' +
                '<div class="form-group"><label>Quantity</label><input type="number" name="ticket_quantity[]"></div>' +
                '<div class="form-group"><label>Status</label><select name="ticket_status[]">' +
                '<option value="available">Available</option>' +
                '<option value="sold-out">Sold Out</option>' +
                '</select></div>' +
                '</div>' +
                '</div>';
            
            document.getElementById('ticketContainer').insertAdjacentHTML('beforeend', html);
        }
    </script>
</body>
</html>
