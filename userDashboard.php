<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Assign the session user to a variable
$user = $_SESSION['user'];
$users = include('database/show-users.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/57b929fbcb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/userDashboard.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admin Dashboard</title>
</head>

<body>
    <div id="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <h1>IMS</h1>
                <img src="./pics/user.jfif" alt="User Image">
                <p>Hello <?= htmlspecialchars($user['first_name']) ?></p>
            </div>
            <ul>
            <li><a href="userDashboard.php"class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="dashboard.php" ><i class="fas fa-shopping-cart"></i>Items Management</a></li>
            <li><a href="#"><i class="fas fa-dollar-sign"></i> Revenue Management</a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Accounts Receivable</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> Configuration</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Stats</a></li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="dashboard_topbar">
                <a><i class="fa fa-navicon"></i></a>
                <a><h2>User Dashboard</h2></a>
                <a href="./database/logout.php" class="logout"><i class="fa fa-power-off"></i>Logout</a>
            </div>

            <div class="content-area">
                <h2>User Main Content</h2>
                
                
            </div>
        </div>
    </div>

</body>
</html>
