<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Assign the session user to a variable
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wait for Role Assignment</title>
    <link rel="stylesheet" href="./public/wait.css">
    <script src="https://kit.fontawesome.com/57b929fbcb.js" crossorigin="anonymous"></script> <!-- Optional: Link to your CSS for styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .wait-container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        .loading {
            margin-top: 20px;
            font-size: 24px;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="wait-container">
    <h1>Wait for Role Assignment</h1>
    <p>Hello, <?= htmlspecialchars($user['first_name']) ?>! Your role is currently being set by the admin.</p>
    <p>Please wait while we process your request.</p>
    <div class="loading">Loading...</div>
    <div class="loading">
        <i class="fas fa-spinner fa-spin"></i> <!-- Font Awesome Spinner -->
    </div>
    <!-- Optional: Include a simple loading animation or gif here -->
</div>

<script>
    // Optional: You can add a timeout or redirect after a certain period
    setTimeout(() => {
        window.location.href = 'adminDashboard.php'; // Redirect to dashboard after 30 seconds
    }, 30000); // 30000 milliseconds = 30 seconds
</script>

</body>
</html>
