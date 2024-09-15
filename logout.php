<?php
// Start session
session_start();

// Destroy the session
session_destroy();

// Redirect to the homepage with a success message
$_SESSION['message'] = "You have been logged out successfully.";
header('Location: index.php');
?>
