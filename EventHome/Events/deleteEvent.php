<?php
require_once '../Config/config.php';

if (!isset($_GET['id'])) {
    header("Location: ../EventUsers/adminDashboard.php");
    exit();
}

$event_id = (int) $_GET['id'];


// 1️⃣ Get image path before deleting
$sql = "SELECT image FROM events WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    header("Location: ../EventUsers/adminDashboard.php?error=1");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $event_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$event  = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$event) {
    header("Location: ../EventUsers/adminDashboard.php?error=1");
    exit();
}


// 2️⃣ Delete event (tickets deleted via CASCADE)
$delete_sql = "DELETE FROM events WHERE id = ?";

$stmt = mysqli_prepare($conn, $delete_sql);
if (!$stmt) {
    header("Location: ../EventUsers/adminDashboard.php?error=1");
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $event_id);

if (mysqli_stmt_execute($stmt)) {

    // Delete image file if exists
    if (!empty($event['image']) && file_exists($event['image'])) {
        unlink($event['image']);
    }

    header("Location: ../EventUsers/adminDashboard.php?deleted=1");
    exit();

} else {
    header("Location: ../EventUsers/adminDashboard.php?error=1");
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <script src="../EventJavascript/Event.js"></script>
</head>
</html>
