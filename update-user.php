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
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();

    $_SESSION['message'] = "User role updated successfully!";
    $_SESSION['message_type'] = "success";
    header('Location: manage-users.php');
}
?>
