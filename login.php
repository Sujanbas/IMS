<?php
session_start();

// Redirect to dashboard if the user is already logged in
if (isset($_SESSION['user'])) {
    header('location: dashboard.php');
    exit();
}

$error_message = '';
if ($_POST) {
    include('database/connection.php');
    
    // Using filter_var to sanitize the email input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare the query to fetch the user by email
    $query = 'SELECT * FROM users WHERE users.email = :email LIMIT 1';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            // Redirect based on user role
            if ($user['role'] == 'admin') {
                header('Location: adminDashboard.php');
            } elseif ($user['role'] == 'user') {
                header('Location: dashboard.php');
            } else {
                header('Location: wait.php');
            }
            exit();
        } else {
            $error_message = 'Invalid password. Please try again.';
        }
    } else {
        $error_message = 'No account found with that email. Please check your email or create a new account.';
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMS Login</title>
    <link rel="stylesheet" type="text/css" href="./public/login.css">
    <link rel="stylesheet" type="text/css" href="./public/nav.css">
</head>
<body>
    <nav id="nav-bar">
        <ul>
            <li><div class="logo">
                <a href="index.html"><img src="./pics/logo.jpeg" alt="Logo"></a> <!-- Corrected image path -->
            </div></li>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
   
    <div class="login-container">
        <h2>IMS Login</h2>
        <form class="login-form" action="login.php" method="POST">
            <input type="email" name="username" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <button type="button" class="create-account-btn" onclick="window.open('create-account.php', '_blank')">Create Account</button>
            <?php if(!empty($error_message)) { ?>
                  <div class="error-message" id="error-message">
                  <p>Error: <?=$error_message?> </p>
                  </div>
            <?php } ?>
          
        </form>
    </div>
</body>
</html>
