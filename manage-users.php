<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Ensure only admin users can access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Fetch users from the database
$sql = "SELECT id, FName, LName, Email, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
                <li><a href="reports.php">Reports</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Manage Users Section -->
    <section class="manage-users">
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['FName']; ?></td>
                        <td><?= $row['LName']; ?></td>
                        <td><?= $row['Email']; ?></td>
                        <td><?= $row['role']; ?></td>
                        <td>
                            <form action="update-user.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <select name="role">
                                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit">Update Role</button>
                            </form>
                            <form action="delete-user.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <footer class="footer">
        <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
    </footer>

</body>
</html>
