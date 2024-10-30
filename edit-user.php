<?php
session_start();

// Redirect to login if not authenticated
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Check if user parameters are set in the URL, and assign them to variables if they exist
$user_id = isset($_GET['id']) ? $_GET['id'] : null;
$first_name = isset($_GET['first_name']) ? $_GET['first_name'] : '';
$last_name = isset($_GET['last_name']) ? $_GET['last_name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : ''; // New addition for capturing email

// Display an error if the user ID is not provided
if (is_null($user_id)) {
    echo "User information is missing.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="./public/admin.css">
</head>
<body>
    <div class="edit-user-container">
        <h2>Edit User</h2>
        <form action="database/update-user.php" method="POST">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>" />
            
            <label for="first_name">First Name:</label>
            <input type="text" name="f_name" id="first_name" value="<?= htmlspecialchars($first_name) ?>" required />
            
            <label for="last_name">Last Name:</label>
            <input type="text" name="l_name" id="last_name" value="<?= htmlspecialchars($last_name) ?>" required />

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required />

            <input type="submit" value="Update User" />
        </form>
    </div>
</body>
</html>
