<?php
session_start();
include 'db_connection.php'; // Include your database connection script

if (!isset($_SESSION['reset_code']) || !isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php"); // Redirect if the session does not exist
    exit();
}

$email = $_SESSION['reset_email']; // Get the email from session
$reset_code = $_SESSION['reset_code']; // Get the reset code from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['reset_code'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT); // Hash the new password

    // Validate the entered reset code
    if ($entered_code === $reset_code) {
        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);
        if ($stmt->execute()) {
            unset($_SESSION['reset_code']); // Clear the reset code
            unset($_SESSION['reset_email']); // Clear the email
            $_SESSION['success_message'] = "Your password has been reset successfully.";
            header("Location: login.php"); // Redirect to the login page
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to reset password. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid reset code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
    <form action="reset_password.php" method="post">
        <h2>Reset Password</h2>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required readonly>
        <input type="text" name="reset_code" placeholder="Enter reset code" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="submit" value="Reset Password">
    </form>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error_message">
            <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success_message">
            <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
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
