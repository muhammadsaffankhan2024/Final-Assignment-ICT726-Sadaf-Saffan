
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Ensure the admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

/// Handle booking deletion
if (isset($_POST['delete_booking'])) {
    $delete_id = $_POST['delete_id'];

    // Delete the booking from the database
    $stmt = $conn->prepare("DELETE FROM rental_reservations WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    $_SESSION['message'] = "Booking deleted successfully!";
    $_SESSION['message_type'] = "success";

    header('Location: manage-bookings.php');
    exit();
}


// Fetch rental reservations from the database
$bookings = $conn->query("SELECT rental_reservations.*, users.FName, users.LName FROM rental_reservations JOIN users ON rental_reservations.user_id = users.id");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Update the booking status in the rental_reservations table
    $stmt = $conn->prepare("UPDATE rental_reservations SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();

    $_SESSION['message'] = "Booking status updated successfully!";
    $_SESSION['message_type'] = "success";

    header('Location: manage-bookings.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Manage Bookings</title>
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


<h1>Manage Bookings</h1>

<?php if (isset($_SESSION['message'])): ?>
    <div class="popup-modal <?= $_SESSION['message_type']; ?>">
        <p><?= $_SESSION['message']; ?></p>
        <button id="closePopup">OK</button>
    </div>
    <script>
    document.getElementById('closePopup').addEventListener('click', function() {
        document.querySelector('.popup-modal').style.display = 'none';
    });
    </script>
    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
<?php endif; ?>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Car Model</th>
        <th>Plan</th>
        <th>Price</th>
        <th>Booking Date</th>
        <th>Return Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php while($booking = $bookings->fetch_assoc()): ?>
    <tr>
        <td><?= $booking['id']; ?></td>
        <td><?= $booking['FName'] . " " . $booking['LName']; ?></td>
        <td><?= $booking['car_model']; ?></td>
        <td><?= $booking['plan']; ?></td>
        <td>$<?= $booking['price']; ?></td>
        <td><?= $booking['booking_date']; ?></td>
        <td><?= $booking['return_date']; ?></td>
        <td><?= ucfirst($booking['status']); ?></td>
        <td>
            <!-- Form to Update Status -->
            <form action="manage-bookings.php" method="POST" style="display:inline;">
                <input type="hidden" name="booking_id" value="<?= $booking['id']; ?>">
                <select name="status">
                    <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="approved" <?= $booking['status'] === 'approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="canceled" <?= $booking['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit" name="update_status">Update</button>
            </form>

            <!-- Form to Delete Booking -->
            <form action="manage-bookings.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                <input type="hidden" name="delete_id" value="<?= $booking['id']; ?>">
                <button type="submit" name="delete_booking" >Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<footer class="footer">
    <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
</footer>

</body>
</html>
