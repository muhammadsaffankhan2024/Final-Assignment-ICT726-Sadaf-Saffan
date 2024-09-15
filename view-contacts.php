
<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Check if the admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle the delete request if the 'delete_id' is provided
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // Ensure it's an integer

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    // Execute the delete operation and handle the result
    if ($stmt->execute()) {
        $_SESSION['message'] = "Contact deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete contact: " . $stmt->error;
        $_SESSION['message_type'] = "failure";
    }

    $stmt->close();

    // Redirect to avoid resubmitting the delete request on refresh
    header('Location: view-contacts.php');
    exit();
}

// Fetch all contacts from the database
$contacts = $conn->query("SELECT * FROM contacts");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Contacts - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


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

    <section class="contacts">
        <h1>Contact Messages</h1>
    <!-- Display success or failure message -->
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

        <!-- Table to display all contacts -->
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                     <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($contact = $contacts->fetch_assoc()): ?>
                <tr>
                    <td><?= $contact['id']; ?></td>
                   <td><?= $contact['name']; ?></td>
                    <td><?= $contact['email']; ?></td>
                    <td><?= $contact['message']; ?></td>
                    <td><?= $contact['submitted_at']; ?></td>
                     <td>
                        <!-- Delete Option with confirmation -->
                <a href="view-contacts.php?delete_id=<?= $contact['id']; ?>" onclick="return confirm('Are you sure you want to delete this contact?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

</body>
</html>
