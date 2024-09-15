<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    $_SESSION['message'] = "You must be logged in to submit a message.";
    $_SESSION['message_type'] = "failure";
    header('Location: contact.php');
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the form inputs
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $user_id = $_SESSION['user_id']; // Get logged-in user's ID

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Prepare the SQL query
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message, user_id) VALUES (?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt) {
            // Bind parameters (4 parameters: name, email, message, user_id)
            $stmt->bind_param("sssi", $name, $email, $message, $user_id);

            // Execute the query and handle the result
            if ($stmt->execute()) {
                $_SESSION['message'] = "Message sent successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                // Show SQL error if it failed
                $_SESSION['message'] = "Failed to send message: " . $stmt->error;
                $_SESSION['message_type'] = "failure";
            }

            // Close the statement
            $stmt->close();
        } else {
            $_SESSION['message'] = "Failed to prepare the statement: " . $conn->error;
            $_SESSION['message_type'] = "failure";
        }
    } else {
        // If form data is missing
        $_SESSION['message'] = "Please fill in all the fields.";
        $_SESSION['message_type'] = "failure";
    }

    // Redirect back to the contact form
    header('Location: contact.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - DriveEasy Rentals</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#closePopup').on('click', function() {
            $('.popup-modal').fadeOut();
        });
    });
    </script>
</head>
<body>
    <!-- Top Header with Contact Info -->
    <div class="top-header">
        <div class="contact-info">
            <div class="address">
                <svg width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                    <path d="M8 0a5.53 5.53 0 0 1 5.5 5.5c0 3.7-5.5 10.5-5.5 10.5S2.5 9.2 2.5 5.5A5.53 5.53 0 0 1 8 0zm0 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                49, Main Street, Newcastle, Australia
            </div>
            <div class="phone">
                <svg width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.599-.163l2.1 2.1a1.745 1.745 0 0 1-.383 2.767L5.233 6.4a12.466 12.466 0 0 0 4.368 4.368l.715-.97a1.745 1.745 0 0 1 2.767-.383l2.1 2.1c.629.629.744 1.63.163 2.358-.544.683-1.26 1.27-2.157 1.679C11.884 16.567 9.018 15.37 6.32 12.67 3.621 9.971 2.424 7.105 3.157 4.686c.41-.897.997-1.613 1.679-2.157z"/>
                </svg>
              +61 415 395 822
            </div>
        </div>
    </div>

    <!-- Main Header with Logo and Navigation -->
    <header class="header">
        <div class="logo">
            <a href="index.html">Drive<span>Easy</span> Rentals</a>
        </div>
        <div class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="safety.html">Safety</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="pricing-plan.html">Pricing Plan</a></li>
                <li><a href="contact.html">Contact</a></li>
                <!-- Conditionally render "Login/Register" or "Logout" based on session -->
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" id="authButton">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Contact Form Section -->
    <section class="contact-form">
        <h1>Contact Us</h1>
        <div class="container">
            <!-- Display success or failure message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="popup-modal <?= $_SESSION['message_type']; ?>">
                    <p><?= $_SESSION['message']; ?></p>
                    <button id="closePopup">OK</button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['message_type']); endif; ?>

            <!-- Contact Form -->
            <form action="contact.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <input type="submit" value="Send Message">
            </form>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
    </footer>

</body>
</html>
