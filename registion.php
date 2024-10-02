<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
        <div class="logo"><img class="carlogo" src="/images/logo.png" alt="car logo"></div>
        <div class="contact">
            <p><span class="bold">Support Mail us :</span> 
            <p>info@gmail.com</p>
        </div>
        <div class="auth-buttons">
            <a href="/login.php">Login</a>
            <a href="/registion.php">Register</a>
        </div>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="#">Cars</a>
        <a href="#">About Us</a>
        <a href="#">Contact</a>
    </nav>
    <div class="background">
        <form action="/register_process.php" method="POST">
            <h2>Register</h2>
            <?php
            if (!empty($error_message)) {
                echo '<div class="error_message">' . htmlspecialchars($error_message) . '</div>';
            }
            ?>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
            <div>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>
    <footer>
        <div class="footer-links">
            <a href="#">About Us</a>
            <a href="#">FAQs</a>
            <a href="#">Privacy</a>
            <a href="#">Terms of Use</a>
            <a href="#">Admin Login</a>
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
