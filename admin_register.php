<?php
include 'db.php';

$message = '';

if (isset($_POST['register'])) {
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $secret_code = $_POST['secret_code'];

    // Secret code validation
    $correct_secret_code = "car#24";
    
    if ($secret_code === $correct_secret_code) {
        // Check if email already exists
        $email_check_query = "SELECT email FROM admin_user WHERE email = ?";
        $stmt = $conn->prepare($email_check_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $message = "This email is already registered. Please use a different email.";
        } else {
            // Proceed with registration
            $query = "INSERT INTO admin_user (admin_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $admin_name, $email, $password);

            if ($stmt->execute()) {
                $message = "Registration successful. You can now log in.";
            } else {
                $message = "Error: " . $stmt->error;
            }
        }
    } else {
        $message = "Incorrect secret code. Registration denied.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>

        .notification {
    color: green; /* or green for success messages */
    font-size: 16px;
    margin-bottom: 10px;
    background-color: aliceblue;
    padding: 10px;
}

        button {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #2980b9;
        }

        /* Additional styling for header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }

        .auth-buttons a {
            color: white;
            margin-left: 10px;
            text-decoration: none;
        }

        .auth-buttons a:hover {
            text-decoration: underline;
        }

        .logo img {
            max-width: 100px;
        }

        .profile-circle {
            border-radius: 50%;
            max-width: 50px;
            margin-right: 10px;
        }
        .cover{
            background-size: cover;
            background-position: center;
            height: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            text-align: center;
            padding: 100px;
        }
        form a{
            color: white;
            text-decoration: none;
            background-color: #3498db;
            padding: 10px;
        }
    </style>
</head>

<body>


<nav>
    <a href="index.php">Home</a>
    <a href="/cars.php">Cars</a>
    <a href="rented_cars.php">View Rented Cars</a>
    <a href="/about.php">About Us</a>
    <a href="/contact.php">Contact</a>
</nav>

<div class="cover">
    <form action="admin_register.php" method="POST">
        <?php if ($message): ?>
            <p class="notification"><?php echo $message; ?></p>
        <?php endif; ?>
        <input type="text" name="admin_name" placeholder="Admin Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="secret_code" placeholder="Enter Secret Code" required>
        <button type="submit" name="register">Register</button>
        <a href="/admin_login.php">Login</a>
    </form>
</div>


<div class="website-title">
    <p class="t"><span class="title">Find the Best</span> Car For You</p> </br>
    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of the text.</p>
</div>



<footer>
    <div class="footer-links">
        <a href="#">About Us</a>
        <a href="#">FAQs</a>
        <a href="#">Privacy</a>
        <a href="#">Terms of Use</a>
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
