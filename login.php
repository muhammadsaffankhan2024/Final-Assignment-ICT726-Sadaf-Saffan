<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Store user information in session
            $_SESSION['loggedin'] = true;
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['role'] = $user['role']; // Store the user's role in session
            
            // Check if the user is an admin
            if ($user['role'] == 'admin') {
                header('Location: admin.php'); 
                $_SESSION['message'] = "Loggedin successful!";
                $_SESSION['message_type'] = "success";// Redirect to admin dashboard
            } else {
                header('Location: index.php');
                $_SESSION['message'] = "Loggedin successful!";
                $_SESSION['message_type'] = "success"; // Redirect to user dashboard
            }
            exit(); // Make sure to exit after header redirection
        } else {
            $_SESSION['message'] = "Incorrect password.";
            $_SESSION['message_type'] = "error";
            header('Location: index.php');
        }
    } else {
        $_SESSION['message'] = "No account found with this email.";
        $_SESSION['message_type'] = "error";
        header('Location: index.php');
    }
}
$conn->close();
?>
