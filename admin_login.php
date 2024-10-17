<?php
// Database connection
include 'db.php';

// Start session
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Simple grid layout for car section */
        

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
    </style>
</head>

<body>


<nav>
    <a href="index.php">Home</a>
    <a href="#">Cars</a>
    <a href="rented_cars.php">View Rented Cars</a>
    <a href="#">About Us</a>
    <a href="#">Contact</a>
</nav>

<div class="cover">

<form action="admin_login.php" method="POST">
    <h1 style="color:palegreen">Admin Login</h1>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
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
        <a href="/add_car.php">Admin Login</a>
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
