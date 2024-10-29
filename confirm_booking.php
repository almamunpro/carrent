<?php
// Include database connection
include 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get the current logged-in user's ID
$email = $_SESSION['username'];
$sql = "SELECT user_id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

if (!$user_id) {
    die("User not found");
}

// Get car ID from URL parameter
$car_id = $_GET['car_id']; // Assuming you are passing car_id via the URL

// Fetch car details from the database
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$stmt->close();

if (!$car) {
    die("Car not found");
}

// Initialize confirmation message variable
$booking_confirmed = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted
    if (isset($_POST['rental_days'], $_POST['name'])) {
        $booking_date = $_POST['booking_date'];
        $return_date = $_POST['return_date'];
        $rental_days = $_POST['rental_days'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        // Calculate total amount
        $total_amount = $rental_days * $car['price_per_day'];

        // Insert the booking into the database
        $sql = "INSERT INTO bookings (user_id, car_id, booking_date, return_date, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("iissd", $user_id, $car_id, $booking_date, $return_date, $total_amount);

        if ($stmt->execute()) {
            // Insert the rented info into the database
            $sql = "INSERT INTO rented_info (user_id, car_id, name, email, phone, message) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iissss", $user_id, $car_id, $name, $email, $phone, $message);
            $stmt->execute();

            $booking_confirmed = true; // Set the confirmation flag
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Add styles for the right-side form */
        .booking-details {
            width: 60%;
            padding: 20px;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .rented-info {
            float: right;
            width: 35%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .confirmation-message {
            display: none;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 20px;
            margin: 20px auto;
            border-radius: 5px;
            text-align: center; /* Center the text */
            width: 300px; /* Set a width for the message box */
            position: fixed; /* Position fixed to center on screen */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Adjust for the element's own dimensions */
            z-index: 1000; /* Ensure it appears above other content */
        }
        .com-btn{
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            background-color: #4CAF50;
            margin-right: 10px;
            text-align: center;
            padding: 10px 20px;
        }
        .form{
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
    </style>
    <script>
        function updateTotalAmount() {
            const rentalDays = document.getElementById('rental_days').value;
            const pricePerDay = <?php echo json_encode($car['price_per_day']); ?>; // Getting price from PHP
            const totalAmount = rentalDays * pricePerDay;
            document.getElementById('total_amount').innerText = totalAmount.toFixed(2); // Update total amount display
        }
    </script>
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
            echo '<a href="/registion.php">Register</a>';
        }
        ?>
    </div>
</header>
<header>
    <h1>Confirm Booking for <?php echo htmlspecialchars($car['name']); ?></h1>
</header>

<div class="booking-details">
    <p><strong>Car Name:</strong> <?php echo htmlspecialchars($car['name']); ?></p>
    <p><strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
    <p><strong>Model:</strong> <?php echo htmlspecialchars($car['model']); ?></p>
    <p><strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?></p>
    <p><strong>Price Per Day:</strong> $<?php echo htmlspecialchars($car['price_per_day']); ?></p>

    <!-- Combined rental and user info form -->
    <form class="form" method="post" action="">
        <label for="rental_days">Number of Days to Rent:</label>
        <input type="number" id="rental_days" name="rental_days" min="1" max="30" value="1" required onchange="updateTotalAmount()">

        <input type="hidden" name="booking_date" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="return_date" value="<?php echo date('Y-m-d', strtotime('+1 day')); // Default return date; adjust as needed ?>">

        <p>Total amount: $<span id="total_amount"><?php echo htmlspecialchars($car['price_per_day']); ?></span></p>

        <h2>Contact Information</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message"></textarea>

        <button class="com-btn" type="submit">Confirm Booking</button>
    </form>
</div>

<!-- Confirmation Message -->
<?php if ($booking_confirmed): ?>
    <div class="confirmation-message" id="confirmationMessage">
        Booking confirmed!
    </div>
<?php endif; ?>

<script>
    // Show confirmation message if booking is confirmed
    <?php if ($booking_confirmed): ?>
        document.addEventListener("DOMContentLoaded", function() {
            var confirmationMessage = document.getElementById("confirmationMessage");
            confirmationMessage.style.display = "block"; // Show the message
            
            setTimeout(function() {
                confirmationMessage.style.display = "none"; // Hide after 2 seconds
                window.location.href = 'index.php'; // Redirect to home page after hiding
            }, 2000);
        });
    <?php endif; ?>
</script>

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
