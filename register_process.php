<?php
session_start();
include 'db.php'; // Connect to your database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "All fields are required.";
        header('Location: registion.php'); // Redirect back to the registration form
        exit();
    }

    // Check if username or email already exists
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Username or Email already exists.";
        header('Location: registion.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; // Save username in session
        header('Location: index.php'); // Redirect to homepage
        exit();
    } else {
        $_SESSION['error_message'] = "Registration failed. Please try again.";
        header('Location: registion.php');
    }
}
?>
