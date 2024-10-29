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
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .about{
            padding-top: 20px;
            font-size: 16px;
            background-image: url("/images/banner-image.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .about h4{
            padding: 10px;
            color: white;
            width: 40%;
            border: 1px solid white;
            background-color: #88ffff30;
        }
        .about h1{
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: aliceblue;
            width: 50%;
        }
    </style>
</head>

<body>
<header>
    <div class="logo"><img class="carlogo" src="/images/logo.png" alt="car logo"></div>
    <div class="contact">
        <p><span class="bold">Support Mail us :</span> 
        <p>info@gmail.com</p>
    </div>
    <div class="auth-buttons">
        <?php
        if (isset($_SESSION['username'])) {
            echo '<div class="profile-section">';
            echo '<img src="/images/cat-profile.png" alt="Profile" class="profile-circle">';
            echo '<span class="username">' . htmlspecialchars($_SESSION['username']) . '</span>';
            echo '<a href="/logout.php" class="logout-btn">Logout</a>';
            echo '</div>';
        } else {
            echo '<a href="/login.php">Login</a>';
            echo '<a href="/register.php">Register</a>';
        }
        ?>
    </div>
</header>

<nav>
    <a href="index.php">Home</a>
    <a href="/cars.php">Cars</a>
    <a href="rented_cars.php">View Rented Cars</a>
    <a href="/about.php">About Us</a>
    <a href="/contact.php">Contact</a>
</nav>

<div class="about">
    <h1>Welcome to Car Rental Service! Our Mission is to provide affordable and reliable car rentals to our customers. Our goal is to make your travel experience as comfortable and enjoyable as possible.  </h1>
    <h4>Discover Our Favorite Cars</h4>
    <h4>Our Car Rental Service is committed to offering the best possible deals and services to our customers. We believe that every car deserves a great ride.  </h4>
    <h4>Book Your Car Today</h4>
    <h4>Our Car Rental Service offers a wide range of car options to help you find the perfect car for your needs. We are always here to help you find the best car for your budget and driving style.  </h4>
    <h4>Book Your Car Today</h4>
    <h4>Our Car Rental Service offers a wide range of car </h4>

</div>

<div class="satisfied-customers">
    <h2>Our Satisfied Customers</h2>
    <div class="customer">
        <div class="customer-circle"><p><span>40+</span> <br>Years in Business</p></div>
        <div class="customer-circle"><p><span>1200+</span> <br>New Cars For Sale</p></div>
        <div class="customer-circle"><p><span>1000+</span> <br>Used Cars For Sale</p></div>
        <div class="customer-circle"><p><span>600+</span> <br>Satisfied Customers</p></div>
    </div>
</div>

<footer>
    <div class="footer-links">
        <a href="#">About Us</a>
        <a href="#">FAQs</a>
        <a href="#">Privacy</a>
        <a href="#">Terms of Use</a>
        <a href="/admin_register.php">Admin Login</a>
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
