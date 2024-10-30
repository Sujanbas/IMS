<?php
session_start();

$table_name = $_SESSION['table'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$encrypted = password_hash($password, PASSWORD_DEFAULT);

include('connection.php'); // Make sure this connects to your database

// Adding the account record 
try {
    // Check if the email already exists in the 'users' table
    $checkQuery = "SELECT COUNT(*) FROM users WHERE email = '".$email."'";
    $stmt = $conn->query($checkQuery);
    $existingUserCount = $stmt->fetchColumn();

    if ($existingUserCount > 0) {
        // Email already exists, set response and redirect
        $response = [
            'sucess' => false,
            'message' => 'A user with this email already exists!'
        ];
    } else {
        // Proceed with adding the new user if email does not exist
        $command = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) 
                    VALUES ('".$first_name."', '".$last_name."', '".$email."', '".$encrypted."', NOW(), NOW())";
        
        $conn->exec($command);
        $response = [
            'sucess' => true,
            'message' => $first_name . ' ' . $last_name. ' You have successfully created an account!! '
        ];
    }
    
} catch (Exception $e) {
    $response = [
        'sucess' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../create-account.php');
?>
