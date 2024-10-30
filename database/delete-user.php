<?php
    $data = $_POST;
    $user_id = (int) $data['user_id'];
    $first_name = (string) $data['f_name'];
    $last_name = (string) $data['l_name'];

    // Deleting the account record
try { 
   
        $command = "DELETE FROM users WHERE id={$user_id}";
        include('connection.php');
    
        $conn->exec($command);
        echo json_encode([
            'success' => true,
            'message' => 'You have successfully deleted an account for ' . $first_name . ' ' . $last_name 
        ]);
       
    }catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error processing your delete request!!!'
    ]);
}



?>