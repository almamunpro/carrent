<?php
session_start();
include 'db_connection.php'; // Include your database connection script

$reset_code = '';
$email_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Simulate generating a reset code
    $reset_code = bin2hex(random_bytes(4)); // Generate a random reset code

    // You would normally send this code via email
    // For testing, we just set it in the session
    $_SESSION['reset_code'] = $reset_code;
    $_SESSION['reset_email'] = $email; // Save the email to use later

    $email_sent = true; // Set flag to show the reset code on the page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="index">
<header>
    <div class="logo"><img class="carlogo" src="/images/logo.png" alt="car logo"></div>
    <div class="contact">
        <p><span class="bold">Support Mail us:</span> info@gmail.com</p>
    </div>
    <div class="auth-buttons">
        <a href="/login.php">Login</a>
        <a href="/registration.php">Register</a>
    </div>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="/cars.php">Cars</a>
    <a href="rented_cars.php">View Rented Cars</a>
    <a href="/about.php">About Us</a>
    <a href="/contact.php">Contact</a>
</nav>

<div class="background">
    <form action="forgot_password.php" method="post">
        <h2>Forgot Password</h2>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="submit" value="Send Reset Code">
    </form>

    <?php if ($email_sent): ?>
        <div class="success_message">
            Reset code has been generated: <strong><?php echo $reset_code; ?></strong>
            <p>Please use this code to reset your password.</p>
            <p><a href="reset_password.php">Click here to reset your password</a></p>
        </div>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-links">
        <a href="#">About Us</a>
        <a href="#">FAQs</a>
        <a href="#">Privacy</a>
        <a href="#">Terms of Use</a>
        <a href="#">Admin Login</a>
    </div>
    <div class="subscribe">
        <h3>Subscribe Newsletter</h3>
        <p>Enter Email Address</p>
        <input type="email" placeholder="Your Email Address">
        <button>Subscribe</button>
        <p>*We send great deals and the latest auto news to our subscribed users every week.</p>
    </div>
</footer>
</body>
</html>
