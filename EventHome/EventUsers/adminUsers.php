<?php
require_once '../Config/config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'deactivated') {
        $message = 'User deactivated successfully';
    } elseif ($_GET['msg'] == 'activated') {
        $message = 'User activated successfully';
    } elseif ($_GET['msg'] == 'deleted') {
        $message = 'User deleted successfully';
    }
}

// Get all users
$sql = "SELECT id, username, email, role, status, created_at FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../Eventcss/adminUsers.css">
    <link rel="stylesheet" href="../../Eventcss/homePage.css">
</head>
<body class="back">

    <nav class="navbar">
         <a href="../homePage.php" class="logo">Event Garden</a>
        <h1>User Management</h1>
        <div class="user-info">
            <span class="badge">ADMIN</span>
            <span>Welcome, <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?></span>
            <a href="adminDashboard.php"  class="browse-btn">Dashboard</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>
    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php
        // Calculate stats
        $total_users = mysqli_num_rows($result);
        $admin_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='admin'"));
        $customer_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='customer'"));
        $active_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE status='active'"));
        $event_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM events"));
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo $total_users; ?></div>
            </div>
            <div class="stat-card">
                <h3>Administrators</h3>
                <div class="number"><?php echo $admin_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Customers</h3>
                <div class="number"><?php echo $customer_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Users</h3>
                <div class="number"><?php echo $active_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Events</h3>
                <div class="number"><?php echo $event_count; ?></div>
            </div>
        </div>
        
        <div class="table-container">
            <h2>All Registered Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($result, 0); // Reset pointer
                    while ($user = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge-role badge-<?php echo $user['role']; ?>">
                                    <?php echo strtoupper($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge-status badge-<?php echo $user['status']; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <?php if ($user['status'] == 'active'): ?>
                                        <a href="adminUserAction.php?action=deactivate&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-deactivate" 
                                           onclick="return confirm('Deactivate this user?')">Deactivate</a>
                                    <?php else: ?>
                                        <a href="adminUserAction.php?action=activate&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-activate">Activate</a>
                                    <?php endif; ?>
                                    <a href="adminUserAction.php?action=delete&id=<?php echo $user['id']; ?>" 
                                       class="btn btn-delete" 
                                       onclick="return confirm('Delete this user permanently?')">Delete</a>
                                <?php else: ?>
                                    <span style="color: #999;">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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