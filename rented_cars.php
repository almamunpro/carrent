<?php
// Include database connection
include 'db.php';
session_start();



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

// Fetch rented cars for the user, including the image path and status
$sql = "SELECT b.car_id, b.booking_date, b.return_date, b.total_amount, b.status,
               c.name, c.model, c.fuel_type, c.seats, 
               c.image_path
        FROM bookings b 
        JOIN cars c ON b.car_id = c.id 
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$rented_cars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented Cars</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .active {
            color: white;
            border: 2px solid white;
            padding: 15px;
            background-color: #333;
            margin-right: 10px;
        }
    </style>
    <script>
        function startCountdown(returnDate, elementId) {
            const returnTime = new Date(returnDate).getTime();

            const countdownInterval = setInterval(() => {
                const now = new Date().getTime();
                const distance = returnTime - now;

                // Time calculations for days, hours, minutes, and seconds
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Display the result
                document.getElementById(elementId).innerHTML = 
                    `${days}d ${hours}h ${minutes}m ${seconds}s`;

                // If the countdown is over, clear the interval
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    document.getElementById(elementId).innerHTML = "Expired";
                }
            }, 1000);
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
            echo '<a href="/register.php">Register</a>';
        }
        ?>
    </div>
</header>
<nav>
    <a href="index.php">Home</a>
    <a href="/cars.php">Cars</a>
    <a class="active" href="rented_cars.php">View Rented Cars</a>
    <a href="/about.php">About Us</a>
    <a href="/contact.php">Contact</a>
</nav>

<header>
    <h1>Your Rented Cars</h1>
</header>

<div class="rented-cars">
    <?php foreach ($rented_cars as $car): ?>
        <div class="car-details">
            <div class="car-info">
                <h3><?php echo htmlspecialchars($car['name']); ?> (<?php echo htmlspecialchars($car['model']); ?>)</h3>
                <p><strong>Fuel Type:</strong> <?php echo htmlspecialchars($car['fuel_type']); ?></p>
                <p><strong>Seats:</strong> <?php echo htmlspecialchars($car['seats']); ?></p>
                <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($car['total_amount']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($car['status']); ?></p>
                <p><strong>Return Date:</strong> <span id="return-date-<?php echo $car['car_id']; ?>"><?php echo htmlspecialchars($car['return_date']); ?></span></p>
                <p><strong>Time Left:</strong> <span id="countdown-<?php echo $car['car_id']; ?>">
                    <?php echo $car['status'] === 'Active' ? '' : 'Inactive'; ?>
                </span></p>
                <?php if ($car['status'] === 'Active'): ?>
                    <script>
                        startCountdown("<?php echo $car['return_date']; ?>T23:59:59", "countdown-<?php echo $car['car_id']; ?>");
                    </script>
                <?php endif; ?>
            </div>
            <div class="car-image">
                <img src="<?php echo htmlspecialchars($car['image_path']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>" style="width: 200px; height: auto;">
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .rented-cars {
        display: flex;
        justify-content: center;
        flex-direction: column;
        gap: 20px;
    }

    .car-details {
        display: flex;
        justify-content: space-between;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }

    .car-info {
        flex: 1;
    }

    .car-image {
        margin-left: 20px;
    }
</style>

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
