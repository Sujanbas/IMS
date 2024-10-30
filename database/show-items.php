<?php
  //  session_start(); // Make sure session is started
    include('connection.php');

    // Get user data from session
    if (isset($_SESSION['user'])) {
        $id = $_SESSION['user']['id']; // Use session user ID

        // Prepare and execute the query to fetch items for the logged-in user
        $stmt = $conn->prepare("SELECT * FROM items WHERE user_id = :id ORDER BY created_at DESC");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $items = $stmt->fetchAll();
        
        return $items;
    } else {
        echo "User is not logged in.";
        $items = [];
    }
?>