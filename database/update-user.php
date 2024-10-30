<?php
// Check if expected POST data exists
$data = $_POST;
$user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;
$first_name = isset($data['f_name']) ? (string) $data['f_name'] : ''; // Updated to match edit-user.php
$last_name = isset($data['l_name']) ? (string) $data['l_name'] : '';   // Updated to match edit-user.php
$email = isset($data['email']) ? (string) $data['email'] : '';

// Ensure required fields are not empty
if ($user_id === 0 || empty($first_name) || empty($last_name) || empty($email)) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields.'
    ]);
    exit;
}

include('connection.php'); // Ensure this file sets up a $conn PDO instance

try {
    // Check if email already exists for a different user
    $emailCheckQuery = "SELECT id FROM users WHERE email = :email AND id != :user_id";
    $stmt = $conn->prepare($emailCheckQuery);
    $stmt->execute(['email' => $email, 'user_id' => $user_id]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'This email is already in use by another user.'
        ]);
        exit;
    }

    // Update user information
    $updateQuery = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, updated_at = NOW() WHERE id = :user_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'user_id' => $user_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'You have successfully updated the account for ' . $first_name . ' ' . $last_name 
    ]);
    header('Location: ../admin.php'); // Redirect after the JSON response
    exit();

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error processing your update request!!!'
    ]);
}
?>
