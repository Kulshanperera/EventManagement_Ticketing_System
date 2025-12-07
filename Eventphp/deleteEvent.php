<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    
    // Get image path before deleting
    $sql = "SELECT image FROM events WHERE id = $event_id";
    $result = mysqli_query($conn, $sql);
    $event = mysqli_fetch_assoc($result);
    
    // Delete event (tickets will be deleted automatically due to CASCADE)
    $delete_sql = "DELETE FROM events WHERE id = $event_id";
    
    if (mysqli_query($conn, $delete_sql)) {
        // Delete image file if exists
        if ($event['image'] && file_exists($event['image'])) {
            unlink($event['image']);
        }
        header("Location: allEventsAdmin.php?deleted=1");
    }   else {
        header("Location: allEventsAdmin.php?error=1");
    }
    } else {
        header("Location: allEventsAdmin.php");
        }
exit();
?>
<!DOCTYPE html>
<html>
<head>
    <script src="../EventJavascript/Event.js"></script>
</head>
</html>
