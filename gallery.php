<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Drive Easy Rentals for inquiries about car rentals. We're here to assist you with your needs.">
    <meta name="keywords" content="contact car rentals, Drive Easy Rentals support, rental inquiries">
    <title>Gallery - DriveEasy Rentals</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.burger-menu').on('click', function() {
            $('.navigation ul').toggleClass('open');
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        // Show login/register modal
        $('#authButton').on('click', function() {
            $('#authModal').fadeIn();
        });

        // Close modal
        $('.close-button').on('click', function() {
            $('#authModal').fadeOut();
        });

        // Toggle between login and register forms
        $('.toggleForm').on('click', function() {
            $('#loginForm').toggle();
            $('#registerForm').toggle();
        });

        // Show popup dialog for failure or success message
        <?php if (isset($_SESSION['message'])): ?>
            $('#messagePopup').fadeIn();
        <?php endif; ?>

        // Close the popup dialog
        $('#closePopup').on('click', function() {
            $('#messagePopup').fadeOut();
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
        <div class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
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
    <!-- Popup Dialog for Success/Failure Messages -->
    <?php if (isset($_SESSION['message'])): ?>
    <div id="messagePopup" class="popup-modal <?= $_SESSION['message_type']; ?>">
        <div class="popup-content">
            <p><?= $_SESSION['message']; ?></p>
            <button id="closePopup">OK</button>
        </div>
    </div>
    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); endif; ?>

    <!-- Authentication Modal (Login/Register) -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>

            <!-- Login Form (Default) -->
            <div id="loginForm">
                <h2>Login</h2>
                <form action="login.php" method="POST">
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
                <p>Don't have an account? <span class="toggleForm">Register</span></p>
            </div>

            <!-- Register Form (Hidden by default) -->
            <div id="registerForm" style="display:none;">
                <h2>Register</h2>
                <form action="register.php" method="POST">
                    <input type="text" name="FName" placeholder="First Name" required>
                    <input type="text" name="LName" placeholder="Last Name" required>
                    <input type="email" name="Email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" minlength="8" required>
                    <select name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <button type="submit">Register</button>
                </form>
                <p>Already have an account? <span class="toggleForm">Login</span></p>
            </div>
        </div>
    </div>


    <section class="gallery">
        <h1>Our Gallery</h1>
        <div class="video-grid">
            <div class="video-item">
              <iframe class="frameborder" width="390" height="608px" src="https://www.youtube.com/embed/YuzClM_OAO0?si=HE3hFFMxSjMBp9Oy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="video-item">
                <iframe class="frameborder" width="390" height="290" src="https://www.youtube.com/embed/h9dTYG1y21k?si=dF8e2PV7Of1ZoS5e" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <div class="video-item">
                <iframe class="frameborder" width="390" height="590" src="https://www.youtube.com/embed/tSGW7Hb3X3s?si=cU49h0zUAyRLMLcO" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
               <iframe class="frameborder" width="390" height="608px" src="https://www.youtube.com/embed/8vPpJIqGMBI?si=pejZ0npIoz8Sx65A" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
                <iframe class="frameborder" width="390" height="310px" src="https://www.youtube.com/embed/Mi0ZWuecT0Q?si=ktb-lv0iHsbf42dE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/wC5RNIkQLxI?si=v3mAf7X63iwDEV9B" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
               <iframe width="560" height="315" src="https://www.youtube.com/embed/ipAxZrOMdvU?si=_Y-Khb_sTh9mqmyu" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/qagMeycmEZA?si=zK1RspzP_kH8ddVQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
			<div class="video-item">
               <iframe width="560" height="315" src="https://www.youtube.com/embed/Zdxdzgy2FjI?si=lT_TkRKSrKmef9cY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
            <!-- Repeat video-item divs to make 3 columns and 4 rows -->
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2024 DriveEasy Rentals. All Rights Reserved.</p>
        <div class="social-icons">
           <a href="https://www.facebook.com/"><img src="images/hoverfb.png" alt="Facebook"></a>
            <a href="https://www.instagram.com"><img src="images/hoverin.png" alt="Instagram"></a>
            <a href="https://twitter.com/login/"><img src="images/hovertw.png" alt="Twitter"></a>
        </div>
    </footer>
</body>
</html>
