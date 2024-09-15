<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Check if the admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if the 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Ensure it's an integer

    // Prepare the delete query
    $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    // Execute and handle the result
    if ($stmt->execute()) {
        $_SESSION['message'] = "Contact deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete contact: " . $stmt->error;
        $_SESSION['message_type'] = "failure";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "No contact ID provided.";
    $_SESSION['message_type'] = "failure";
}

// Redirect back to admin dashboard
header('Location: admin_dashboard.php');
exit();
?>
