<?php
session_start();

// Ensure only admin users can access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Drive Easy Rentals</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Admin Header -->
    <header class="admin-header">
        <div class="logo">
            <a href="admin.php">Admin<span>Dashboard</span></a>
        </div>
        <nav class="admin-navigation">
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
                <li><a href="manage-bookings.php">Manage Bookings</a></li>
                <li><a href="view-contacts.php">View Contacts</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Admin Dashboard Welcome Section -->
    <section class="admin-welcome">
        <h1>Welcome, Admin</h1>
        <p>Manage the platform using the sections below.</p>
    </section>

    <!-- Admin Action Section -->
    <section class="admin-actions">
        <div class="action-box">
            <h2>Manage Users</h2>
            <p>View and manage registered users.</p>
            <a href="manage-users.php" class="cta-button">Go to User Management</a>
        </div>
        <div class="action-box">
            <h2>Manage Bookings</h2>
            <p>View and manage all rental bookings.</p>
            <a href="manage-bookings.php" class="cta-button">Go to Booking Management</a>
        </div>
        <div class="action-box">
            <h2>View Contacts</h2>
            <p>View and delete Contacts.</p>
            <a href="view-contacts.php" class="cta-button">Go to Contacts Management</a>
        </div>
        
    </section>
    <section class="admin-actions">

        <div class="action-box">
            <h2>Reports</h2>
            <p>View system reports and statistics.</p>
            <a href="reports.php" class="cta-button">View Reports</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
    </footer>

</body>
</html>
