<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Ensure only admin users can access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    $_SESSION['message'] = "Booking status updated successfully!";
    $_SESSION['message_type'] = "success";
    header('Location: manage-bookings.php');
}
?>
