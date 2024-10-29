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

.car-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .car {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .car img {
            max-width: 100%;
            height: auto;
        }

        .car-details h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .car-details p {
            margin: 5px 0;
        }

        .car-price {
            color: #e74c3c;
            font-weight: bold;
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
        .active{
            color: white;
            border: 2px solid white;
            padding: 15px;
            background-color: #333;
            margin-right: 10px;
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
    <a class="active" href="/cars.php">Cars</a>
    <a href="rented_cars.php">View Rented Cars</a>
    <a href="/about.php">About Us</a>
    <a href="/contact.php">Contact</a>
</nav>

<div class="car-section">
    <?php
    // Prepare the SQL query with a prepared statement
    $sql = "SELECT * FROM cars WHERE available = 1 ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        echo "Error: " . $conn->error;
    } elseif ($result->num_rows > 0) {
        // Output data for each car
        while ($row = $result->fetch_assoc()) {
            echo '<div class="car">';
            echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo '<div class="car-details">';
            echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['fuel_type']) . ' | ' . htmlspecialchars($row['model']) . ' Model | ' . htmlspecialchars($row['seats']) . ' seats</p>';
            echo '<p class="car-price">$' . htmlspecialchars($row['price_per_day']) . ' /Day</p>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<a href="/book_car.php?car_id=' . htmlspecialchars($row['id']) . '"><button>Book Now</button></a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No cars available.";
    }

    $conn->close();
    ?>
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
