<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Homepage</title>
    <link rel="stylesheet" type="text/css" href="./public/index.css">
    <link rel="stylesheet" type="text/css" href="./public/nav.css">
</head>
<body>
    <!-- Navigation bar -->
    <?php include('partials/navbar.php')?>

    <!-- Home content -->
    <div class="home">
        <h2>Welcome to the Inventory Management System</h2>
        <p>Our Inventory Management System (IMS) helps you keep track of your inventory, manage stock levels, and ensure that your business operations run smoothly. Whether you're looking to add new products, review stock status, or generate reports, our system is designed to be user-friendly and efficient.</p>
        
        <div class="container">
            <h3>Features of IMS:</h3>
            <ul class="features-list">
                <li>
                    <div class="feature">
                        <img src="./pics/stopwatch.jpg" alt="Real-time inventory tracking">
                        <span>Real-time inventory tracking</span>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <img src="./pics/wheel.jpg" alt="Automated stock level updates">
                        <span>Automated stock level updates</span>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <img src="./pics/doc.jpg" alt="Inventory categorization and reporting">
                        <span>Inventory categorization and reporting</span>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <img src="./pics/hand.jpg" alt="Supplier and product management">
                        <span>Supplier and product management</span>
                    </div>
                </li>
                <li>
                    <div class="feature">
                        <img src="./pics/bell.jpg" alt="Low stock alerts and notifications">
                        <span>Low stock alerts and notifications</span>
                    </div>
                </li>
            </ul>
        </div>

        <h3>Why Choose IMS?</h3>
        <p>Our platform ensures that businesses can maintain optimal inventory levels, reduce stock discrepancies, and streamline operations to enhance efficiency and profitability. From small businesses to large enterprises, IMS can scale to meet your needs.</p>

        <!-- Get Started Section -->
        <div class="get-started">
            <h3>Get Started</h3>
            <p>Create an account to unlock all features of our Inventory Management System.</p>
            <a href="create-account.php" class="button">Create Account</a>
        </div>
    </div>

    <!-- Map showing current location -->
    <div class="map">
        <h3><strong>Find Us Here</strong></h3>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1630.9782377802838!2d-86.2911!3d36.2083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x886480d95bf42905%3A0x946a4344e535697!2sCumberland%20University!5e0!3m2!1sen!2sus!4v1602940985432!5m2!1sen!2sus"
                width="700" height="450" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
            </iframe>
        </div>
    </div>

    <style>
        /* Button styling */
       
    </style>

</body>
</html>
