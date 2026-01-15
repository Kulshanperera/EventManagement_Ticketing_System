<?php
require_once '../Config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    redirect('adminUsers.php');
}

$action = $_GET['action'];
$user_id = intval($_GET['id']);

// Prevent admin from acting on themselves
if ($user_id == $_SESSION['user_id']) {
    redirect('adminUsers.php?msg=error');
}

switch ($action) {
    case 'deactivate':
        $sql = "UPDATE users SET status = 'deactivated' WHERE id = $user_id";
        mysqli_query($conn, $sql);
        redirect('adminUsers.php?msg=deactivated');
        break;
        
    case 'activate':
        $sql = "UPDATE users SET status = 'active' WHERE id = $user_id";
        mysqli_query($conn, $sql);
        redirect('adminUsers.php?msg=activated');
        break;
        
    case 'delete':
        $sql = "DELETE FROM users WHERE id = $user_id";
        mysqli_query($conn, $sql);
        redirect('adminUsers.php?msg=deleted');
        break;
        
    default:
        redirect('adminUsers.php');
}
?>