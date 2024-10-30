<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

// Check if expected POST data exists
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$role = isset($_POST['role']) ? $_POST['role'] : '';

// Ensure required fields are provided
if ($user_id === 0 || empty($role)) {
    echo "Missing user ID or role.";
    exit;
}

include('connection.php'); // Database connection

try {
    // Update user role in the database
    $query = "UPDATE users SET role = :role, updated_at = NOW() WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['role' => $role, 'user_id' => $user_id]);

    // Redirect back to admin dashboard with success message
    header('Location: ../adminDashboard.php?status=success');
    exit();

} catch (Exception $e) {
    echo "Error updating role: " . $e->getMessage();
}
?>
