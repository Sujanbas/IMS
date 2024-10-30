<?php
// Include the database connection
include('connection.php');

// Check for the action parameter
if (isset($_GET['action']) && $_GET['action'] === 'user-registrations') {
    // Prepare the SQL query to count user registrations per day
    $query = "
        SELECT 
            DATE(created_at) AS date,
            COUNT(*) AS registrations
        FROM users
        GROUP BY date
        ORDER BY date ASC
    ";

    // Execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();

        $data = [];
        
        // Fetch the results
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // Use fetch() with PDO::FETCH_ASSOC
            $data[] = [
                'date' => $row['date'], // This will be in 'YYYY-MM-DD' format
                'registrations' => (int)$row['registrations']
            ];
        }

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // Handle query error
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed.']);
    }
} else {
    // Handle invalid action
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action specified.']);
}

// Close the database connection
$conn = null; // Close the connection
?>
