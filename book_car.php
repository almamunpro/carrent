<?php
// Database connection
include 'db.php';
// Start session
session_start();

// Fetch the car details based on car_id
if (isset($_GET['car_id'])) {
    $car_id = intval($_GET['car_id']);

    // Prepare the SQL query to fetch car details
    $sql = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if car exists
    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();
    } else {
        echo "Car not found.";
        exit;
    }
} else {
    echo "Invalid car ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Car - <?php echo htmlspecialchars($car['name']); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Styling for the car details container */
        .car-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin: 40px auto;
            width: 80%;
            max-width: 1200px;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .car-container img {
            max-width: 100%;
            max-height: 300px;
            object-fit: cover;
            height: 300px;
            border-radius: 5px;
            margin-right: 20px;
        }

        .car-details {
            max-width: 600px;
        }

        .car-details h2 {
            margin-bottom: 10px;
        }

        .car-details p {
            margin-bottom: 10px;
        }

        .car-price {
            color: #e74c3c;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
<header>
    <div class="logo"><img class="carlogo" src="/images/logo.png" alt="car logo"></div>
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

<div class="car-container">
    <div class="car-image">
        <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
    </div>
    <div class="car-details">
        <h2><?php echo htmlspecialchars($car['name']); ?></h2>
        <p><strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
        <p><strong>Model:</strong> <?php echo htmlspecialchars($car['model']); ?> Model</p>
        <p><strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?></p>
        <p class="car-price">$<?php echo htmlspecialchars($car['price_per_day']); ?> /Day</p>
        <p><?php echo htmlspecialchars($car['description']); ?></p>
        <a href="/confirm_booking.php?car_id=<?php echo htmlspecialchars($car['id']); ?>"><button>Confirm Booking</button></a>
    </div>
</div>

</body>

</html>
