<?php
// Database configuration
$servername = 'localhost'; // Your MySQL server address
$username = 'root';      // Your MySQL username
$password = ''; // Your MySQL password
//$database = 'imsdb'; // Your database name

try{
    $conn = new PDO("mysql:host = $servername;dbname=imsdb", $username,$password);

// Create a connection
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo 'connection sucessful.';
} catch (\Exception $e){
    $error_message = $e->getMessage(); 
}

?>