<?php
// Include database connection
include 'db.php';



// Handle form submission to approve or reject bookings
$statusMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'] === 'Accept' ? 'Accepted' : 'Rejected';

    $sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $action, $booking_id);
    if ($stmt->execute()) {
        $statusMessage = $action === 'Accepted' ? 'Request Accepted!' : 'Request Rejected!';
    }
    $stmt->close();
}

// Retrieve all pending booking requests
$sql = "SELECT b.booking_id, b.booking_date, b.total_amount, u.username,
               c.name AS car_name, c.model AS car_model
        FROM bookings b
        JOIN users u ON b.user_id = u.user_id
        JOIN cars c ON b.car_id = c.id
        WHERE b.status = 'Pending'";
$result = $conn->query($sql);
$pending_requests = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .approval-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .request-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn-accept {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn-reject {
            padding: 8px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        /* Popup notification styles */
        .popup-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background-color: green;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            text-align: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 1000;
        }
        .popup-notification.show {
            opacity: .5;
            visibility: visible;
        }
    </style>
</head>
<body>
<header>
        <nav>
            <a href="add_car.php">Home</a>
            <a href="view_cars.php">View Cars</a>
            <a href="admin_approval.php">admin_approval</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
<header>
    <h1>Admin - Approve or Reject Booking Requests</h1>
</header>

<div class="approval-container">
    <?php if (!empty($pending_requests)): ?>
        <?php foreach ($pending_requests as $request): ?>
            <div class="request-item">
                <div class="details">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($request['username']); ?></p>
                    <p><strong>Car:</strong> <?php echo htmlspecialchars($request['car_name']); ?> (<?php echo htmlspecialchars($request['car_model']); ?>)</p>
                    <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($request['booking_date']); ?></p>
                    <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($request['total_amount']); ?></p>
                </div>
                <div class="actions">
                    <form action="admin_approval.php" method="POST" onsubmit="showPopup('<?php echo $statusMessage; ?>')">
                        <input type="hidden" name="booking_id" value="<?php echo $request['booking_id']; ?>">
                        <button type="submit" name="action" value="Accept" class="btn-accept">Accept</button>
                        <button type="submit" name="action" value="Reject" class="btn-reject">Reject</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No pending requests at the moment.</p>
    <?php endif; ?>
</div>

<!-- Popup Notification -->
<div class="popup-notification" id="popupNotification">
    <?php echo htmlspecialchars($statusMessage); ?>
</div>

<script>
    // Show the popup notification with the status message
    function showPopup(message) {
        const popup = document.getElementById("popupNotification");
        popup.textContent = message;
        popup.classList.add("show");

        // Hide the popup after 3 seconds
        setTimeout(() => {
            popup.classList.remove("show");
        }, 3000);
    }

    // Automatically trigger the popup if there's a status message
    <?php if (!empty($statusMessage)): ?>
        showPopup("<?php echo $statusMessage; ?>");
    <?php endif; ?>
</script>

</body>
</html>
