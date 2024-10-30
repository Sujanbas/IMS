<?php
    //session_start();
    include('connection.php');

    $stmt =$conn->prepare("SELECT * FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
  
?>