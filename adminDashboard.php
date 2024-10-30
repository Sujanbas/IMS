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

// Check for users with null roles
$nullRoleUsers = array_filter($users, function($user) {
    return empty($user['role']);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/57b929fbcb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./public/adminDashboard.css">
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
            <li><a href="adminDashboard.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="admin.php" class=""><i class="fas fa-users-cog"></i> User Management</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="dashboard_topbar">
            <a><i class="fa fa-navicon"></i></a>
            <a><h2>Admin Dashboard</h2></a>
            <a href="./database/logout.php" class="logout"><i class="fa fa-power-off"></i>Logout</a>
        </div>

        <?php if (!empty($nullRoleUsers)): ?>
            <div class="notification-bar">
                <p>Reminder: There are users without assigned roles. Please assign roles to ensure proper access.</p>
            </div>
        <?php endif; ?>

        <div class="content-area">
            <h2>User Registrations Chart</h2>
            <div id="chart_div"></div> <!-- Container for the Google Chart -->
            
            <div class="user_role">
                <h2>User Roles</h2>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Current Role</th>
                            <th>Assign Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $user) { ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($user['first_name']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td>
                                    <form method="POST" action="database/setRoles.php" onsubmit="return confirmRoleChange()">
                                        <select name="role" required>
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>"/>
                                </td>
                                <td>
                                    <input type="submit" value="Submit"/>
                                    </form> <!-- Close form here to avoid nesting -->
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmRoleChange() {
        return confirm("Do you want to change the role?");
    }

    async function fetchUserRegistrations() {
        try {
            const response = await fetch('./database/userChart.php?action=user-registrations');
            const data = await response.json();

            console.log('Fetched data from server:', data);

            if (Array.isArray(data) && data.length > 0) {
                return data.map(row => [new Date(row.date), row.registrations]);
            } else {
                throw new Error('Invalid data format or empty data');
            }
        } catch (error) {
            console.error('Error fetching user registrations:', error);
            return [];
        }
    }

    async function drawChart() {
        google.charts.load('current', { packages: ['corechart', 'bar'] });
        google.charts.setOnLoadCallback(async () => {
            const data = await fetchUserRegistrations();
            
            const chartData = new google.visualization.DataTable();
            chartData.addColumn('date', 'Date');
            chartData.addColumn('number', 'Registrations');
            chartData.addRows(data);

            console.log('Chart data prepared:', chartData);

            const options = {
                title: 'User Registrations Over Time',
                hAxis: {
                    title: 'Date',
                    format: 'MMM dd, yyyy'
                },
                vAxis: {
                    title: 'Registrations',
                    minValue: 0
                },
                colors: ['#1b9e77']
            };

            const chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            chart.draw(chartData, options);
        });
    }

    drawChart();
</script>

</body>
</html>
