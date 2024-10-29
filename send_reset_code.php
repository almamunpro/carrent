<?php
session_start();
include 'db_connection.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate reset code
        $reset_code = bin2hex(random_bytes(16)); // Generate a secure random code

        // Store the reset code in the database (you might need a new column for this)
        $stmt = $conn->prepare("UPDATE users SET reset_code = ? WHERE email = ?");
        $stmt->bind_param("ss", $reset_code, $email);
        $stmt->execute();

        // Send reset code via email
        $subject = "Password Reset Request";
        $message = "Your reset code is: " . $reset_code . "\nUse this code to reset your password.";
        $headers = "From: info@gmail.com";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['success_message'] = "Reset code has been sent to your email.";
        } else {
            $_SESSION['error_message'] = "Failed to send email. Please try again later.";
        }

        // Redirect to the same page to show the message
        header("Location: forgot_password.php");
        exit();
    } else {
        $_SESSION['error_message'] = "No account found with that email.";
        header("Location: forgot_password.php");
        exit();
    }
}
// After sending the reset code
$_SESSION['success_message'] = "Reset code has been sent to your email.";
header("Location: reset_password.php?email=" . urlencode($email));
exit();

