<?php
// Start the session if needed
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = $_POST['model'] ?? null;
    $fuel_type = $_POST['fuel_type'] ?? null;
    $year = $_POST['year'] ?? null;
    $seats = $_POST['seats'] ?? null;
    $price = $_POST['price'] ?? null;
    $description = $_POST['description'] ?? null;

    // Validate image file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Ensure the uploaded file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // JPG, PNG, GIF
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if (in_array($fileType, $allowedTypes)) {
            // Define the upload directory and move the file
            $uploadDir = 'uploads/';
            $image_path = $uploadDir . basename($_FILES['image']['name']);
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                echo "Error uploading image.";
                $image_path = null; // Set to null if upload failed
            }
        } else {
            echo "Invalid image format. Only JPEG, PNG, and GIF are allowed.";
            $image_path = null;
        }
    } else {
        echo "Image file not uploaded.";
        $image_path = null;
    }

    // Check if all required fields are filled
    if ($model && $fuel_type && $year && $seats && $price && $description && $image_path) {
        // Database connection
        $conn = new mysqli('localhost', 'root', '', 'car_rental_bd');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert the car details into the database
        $stmt = $conn->prepare("INSERT INTO cars (model, fuel_type, year, seats, price, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiiss", $model, $fuel_type, $year, $seats, $price, $description, $image_path);

        if ($stmt->execute()) {
            // On successful car addition, redirect to the homepage
            echo "<script>alert('New car added successfully.'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car | Car Rental Service</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="addCar">
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="view_cars.php">View Cars</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="addcarcontainer">
        <form class="addform" action="add_car.php" method="post" enctype="multipart/form-data">
            <h1>Add a New Car</h1>
            <div>
                <label for="model">Car Model:</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div>
                <label for="fuel_type">Fuel Type:</label>
                <select id="fuel_type" name="fuel_type" required>
                    <option value="petrol">Petrol</option>
                    <option value="diesel">Diesel</option>
                    <option value="electric">Electric</option>
                    <option value="hybrid">Hybrid</option>
                </select>
            </div>
            <div>
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" required>
            </div>
            <div>
                <label for="seats">Number of Seats:</label>
                <input type="number" id="seats" name="seats" required>
            </div>
            <div>
                <label for="price">Price per Day:</label>
                <input type="number" id="price" name="price" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <label for="image">Car Image:</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif" required>
            </div>
            <button type="submit">Add Car</button>
        </form>
    </main>
</body>
</html>
