<?php
// Include database connection
include 'db.php';
session_start();



// Fetch only active bookings with car and user details
$sql = "
    SELECT 
        bookings.booking_id,          /* Updated column name */
        bookings.booking_date,
        bookings.return_date,
        bookings.total_amount,
        bookings.status,
        cars.name AS car_name,
        cars.model AS car_model,
        cars.fuel_type,
        cars.seats,
        cars.price_per_day,
        users.username AS renter_name
    FROM bookings
    JOIN cars ON bookings.car_id = cars.id
    JOIN users ON bookings.user_id = users.user_id  /* Updated to match column name */
    WHERE bookings.status = 'Accepted'
    ORDER BY bookings.booking_date DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Rented Cars</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f5f5f5;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
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
        <nav>
            <a href="index.php">Home</a>
            <a href="view_cars.php">View Cars</a>
            <a class="active" href="all_rented_car.php">View Rented Cars</a>
            <a href="admin_approval.php">admin_approval</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
<div class="container">
    <h1>Active Rented Cars</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Car Name</th>
                    <th>Model</th>
                    <th>Fuel Type</th>
                    <th>Seats</th>
                    <th>Price Per Day</th>
                    <th>Renter Name</th>
                    <th>Booking Date</th>
                    <th>Return Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['car_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['car_model']); ?></td>
                        <td><?php echo htmlspecialchars($row['fuel_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['seats']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['price_per_day']); ?></td>
                        <td><?php echo htmlspecialchars($row['renter_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['total_amount']); ?></td>
                        <td class="status-active">Active</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No active cars are currently rented out.</p>
    <?php endif; ?>

</div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
