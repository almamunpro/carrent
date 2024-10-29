<?php
session_start();
include 'db.php'; // Ensure you have a proper DB connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to check if the user exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['username'] = $user['username']; // Storing the username in session
            header('Location: index.php'); // Redirect to the homepage
            exit();
        } else {
            $_SESSION['error_message'] = "Incorrect password.";
            header('Location: login.php'); // Redirect back to login page
            exit();
        }
    } else {
        $_SESSION['error_message'] = "No account found with that username.";
        header('Location: login.php');
        exit();
    }
}
?>
