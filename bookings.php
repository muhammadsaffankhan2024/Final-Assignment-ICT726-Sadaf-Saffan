<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'easydrive');

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_model = $_POST['car_model'];
    $plan = $_POST['plan'];
    $price = $_POST['price'];
    $booking_date = $_POST['booking_date'];
    $return_date = $_POST['return_date'];

    // Insert booking data into the database
    $stmt = $conn->prepare("INSERT INTO rental_reservations (user_id, car_model, plan, price, booking_date, return_date, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issdss", $user_id, $car_model, $plan, $price, $booking_date, $return_date);
    $stmt->execute();

    $_SESSION['message'] = "Booking successful! Status is pending.";
    $_SESSION['message_type'] = "success";

    header('Location: bookings.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Book a Car</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Update price based on plan selection
        $('#plan').on('change', function() {
            var selectedPlan = $(this).val();
            var price = 0;
            
            if (selectedPlan === 'Economy') price = 19.99;
            else if (selectedPlan === 'Standard') price = 29.99;
            else if (selectedPlan === 'Luxury') price = 49.99;
            else if (selectedPlan === 'Elite') price = 69.99;

            $('#price').val(price);
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
            <a href="index.php">Drive<span>Easy</span> Rentals</a>
        </div>
        <nav class="navigation">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="safety.php">Safety</a></li>
                <li><a href="gallery.php">Gallery</a></li>

                <li><a href="pricing-plan.php">Pricing Plan</a></li>
                <li><a href="bookings.php">Bookings</a></li>
                <li><a href="contact.php">Contact</a></li>

                <!-- Conditionally render "Login/Register" or "Logout" based on session -->
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="#" id="authButton">Login/Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

<div class="booking-form">
    <h1>Book Your Car</h1>

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

    <form action="bookings.php" method="POST">
        <label for="car_model">Car Model:</label>
        <select name="car_model" id="car_model" required>
            <option value="Toyota Camry">Toyota Camry</option>
            <option value="Honda Accord">Honda Accord</option>
            <option value="BMW 5 Series">BMW 5 Series</option>
            <option value="Mercedes E-Class">Mercedes E-Class</option>
        </select>

        <label for="plan">Choose Plan:</label>
        <select name="plan" id="plan" required>
            <option value="Economy">Economy - $19.99/day</option>
            <option value="Standard">Standard - $29.99/day</option>
            <option value="Luxury">Luxury - $49.99/day</option>
            <option value="Elite">Elite - $69.99/day</option>
        </select>

        <label for="price">Price (per day):</label>
        <input type="text" name="price" id="price" readonly>

        <label for="booking_date">Booking Date:</label>
        <input type="date" name="booking_date" id="booking_date" required>

        <label for="return_date">Return Date:</label>
        <input type="date" name="return_date" id="return_date" required>

        <button type="submit">Book Now</button>
    </form>
</div>


<footer class="footer">
    <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
</footer>

</body>
</html>
