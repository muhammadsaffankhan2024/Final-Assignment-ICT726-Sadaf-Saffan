<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $FName = $_POST['FName'];
    $LName = $_POST['LName'];
    $email = $_POST['Email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        $_SESSION['message_type'] = "error";
        header('Location: index.php');
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Email is already registered.";
        $_SESSION['message_type'] = "error";
        header('Location: index.php');
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (FName, LName, Email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $FName, $LName, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['message'] = "Registration successful!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error occurred during registration.";
            $_SESSION['message_type'] = "error";
        }
        header('Location: index.php');
    }
    $stmt->close();
}
$conn->close();
?>
