<?php
    // Check if expected POST data exists
    $data = $_POST;
    $item_name = $_POST['item_name'];
    $item_description = $_POST['item_description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $user_id =$user['id'];
    $item_id = $item['item_id'];

    // Ensure required fields are not empty
    if ($user_id === 0 || empty($item_name) || empty($item_description) || empty($quantity)|| empty($price)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields.'
        ]);
        exit;
      } 
        include('connection.php'); // Ensure this file sets up a $conn PDO instance

        try {
            // Check if item already exists
            $itemCheckQuery = "SELECT id FROM items WHERE item_name = :item_name;
            $stmt = $conn->prepare($emailCheckQuery);
            $stmt->execute(['item_name' => $item_name, 'user_id' => $user_id]);
            
            if ($stmt->fetch()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'This item already exist.'
                ]);
                exit;
            }
        
            // Update item information
            $updateQuery = "UPDATE items SET item_name = :item_name, quantity = :quantity, item_description = :item_description, updated_at = NOW() WHERE id = :user_id";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->execute([
                'item_name' => $item_name,
                'quantity' => $quantity,
                'item_description' => $item_description,
                'user_id' => $user_id,
                'item_id' => $item_id
            ]);
        
            echo json_encode([
                'success' => true,
                'message' => 'You have successfully updated the item: ' . $item_name
            ]);
            header('Location: ../dashboard.php'); // Redirect after the JSON response
            exit();
        
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error processing your update request!!!'
            ]);
        }  
?>