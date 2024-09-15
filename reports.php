<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Ensure only admin users can access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Fetch data for reports
// Exclude admins from the total user count
$totalUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'user'")->fetch_assoc()['total'];
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM rental_reservations")->fetch_assoc()['total'];
$pendingBookings = $conn->query("SELECT COUNT(*) AS total FROM rental_reservations WHERE status = 'pending'")->fetch_assoc()['total'];
$approvedBookings = $conn->query("SELECT COUNT(*) AS total FROM rental_reservations WHERE status = 'approved'")->fetch_assoc()['total'];
$canceledBookings = $conn->query("SELECT COUNT(*) AS total FROM rental_reservations WHERE status = 'canceled'")->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="style.css">
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <!-- Reports Section -->
    <section class="reports">
        <h1>System Reports</h1>

        <!-- Container for both charts -->
        <div class="charts-container">
            <!-- Total Users and Bookings Bar Chart (Left) -->
            <div class="chart-left">
                <h2>Total Users and Bookings</h2>
                <canvas id="userBookingChart" width="100" height="100"></canvas>
            </div>

            <!-- Booking Status Pie Chart (Right) -->
            <div class="chart-right">
                <h2>Booking Status Distribution</h2>
                <canvas id="bookingStatusChart" width="100" height="100"></canvas>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
    </footer>

    <!-- Chart.js Configuration -->
    <script>
        // Total Users and Total Bookings Bar Chart
        var ctx = document.getElementById('userBookingChart').getContext('2d');
        var userBookingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Users', 'Total Bookings'],
                datasets: [{
                    label: 'Count',
                    data: [<?= $totalUsers; ?>, <?= $totalBookings; ?>],
                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(75, 192, 192, 0.6)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Booking Status Pie Chart
        var ctx2 = document.getElementById('bookingStatusChart').getContext('2d');
        var bookingStatusChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Approved', 'Canceled'],
                datasets: [{
                    data: [<?= $pendingBookings; ?>, <?= $approvedBookings; ?>, <?= $canceledBookings; ?>],
                    backgroundColor: ['rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                    borderColor: ['rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

</body>
</html>
