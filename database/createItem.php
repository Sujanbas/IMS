<?php
session_start();
 // Ensure user is logged in and retrieve user ID from the session
if (!isset($_SESSION['user'])) {
    die("Error: User not logged in.");
}
$user = $_SESSION['user'];
$user_id = $user['id']; // Get the user_id from the session


$item_name = $_POST['item_name'];
$item_description = $_POST['item_description'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$user_id =$user['id']; // Assuming user_id is stored in session

include('connection.php');

// Adding items to the record
try { 
    // Check if the item already exists in the 'items' table
    $checkQuery = "SELECT COUNT(*) FROM items WHERE item_name = :item_name";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute(['item_name' => $item_name]);
    $existingItemCount = $stmt->fetchColumn();

    if ($existingItemCount > 0) {
        // Item already exists, set response and redirect
        $response = [
            'success' => false,
            'message' => 'An item with this name already exists!'
        ];
    } else {  
        // Prepare the insert statement
        $command = "INSERT INTO items (item_name, item_description, quantity, price, user_id, created_at, updated_at) 
                    VALUES (:item_name, :item_description, :quantity, :price, :user_id, NOW(), NOW())";

        $stmt = $conn->prepare($command);
        $stmt->execute([
            'item_name' => $item_name,
            'item_description' => $item_description,
            'quantity' => $quantity,
            'price' => $price,
            'user_id' => $user_id // Include user_id
        ]);

        $response = [
            'success' => true,
            'message' => $item_name . ' has been successfully created!'
        ];
    }
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

$_SESSION['response'] = $response;
header('location: ../dashboard.php');
?>
