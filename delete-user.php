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

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['message'] = "User deleted successfully!";
    $_SESSION['message_type'] = "success";
    header('Location: manage-users.php');
}
?>
