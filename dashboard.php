<?php
    session_start();

    // If the user is not logged in, redirect to login page
    if(!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    // Assign the session user to a variable
    $user = $_SESSION['user'];
    $users = include('database/show-users.php');
    $items = include('database/show-items.php');
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/57b929fbcb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/useritems.css">
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
            <li><a href="userDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="dashboard.php" class="active"><i class="fas fa-shopping-cart"></i>Items Management</a></li>
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
                <a><h2>User Main Content</h2></a>
                <a href="./database/logout.php" class="logout"><i class="fa fa-power-off"></i>Logout</a>
            </div>

            <div class="content-area">
                <!--add items -->
                <div class="add-item-container">
                        <h2>Add Items</h2>
                        <form class="add-item-form" action="database/createitem.php" method="POST"> 
                            <input type="text" name="item_name" placeholder="Item Name" id="item_name" required />
                            <input type="number" name="quantity" placeholder="Quantity" id="quantity" min="1" required />
                            <input type="number" name="price" placeholder="Price" id="price" step="0.01" min="0" required />
                            <input type="text" name="item_description" placeholder="Description" id="item_description" required 
                            style="width: 90%; height: 80px;">
                                 <input type="submit" value="Add Item" />
                        </form> 
                        <?php
                            if (isset($_SESSION['response'])) { 
                                $responseMessage = $_SESSION['response']['message'];
                                $is_success = $_SESSION['response']['success']; // Fixed the typo from 'sucess' to 'success'
                            ?>

                            <div class="error-message" id="error-message">
                                <p class="error_message <?= $is_success ? 'error_message_True' : 'error_message_False' ?>">
                                    <?= htmlspecialchars($responseMessage) ?>
                                </p>
                            </div>

                            <?php 
                                unset($_SESSION['response']); 
                            } 
                            ?>

                    </div>

                        <!-- Display Items list -->
                    <div class="items-container">
                       <!-- Search Bar -->
                        <div class="search-container">
                            <input class="search-input" Type="text" id="search-input" placeholder="Search items..." onkeyup="searchItems()" />
                            <button class="search-button" id="search-button" onclick="searchItems()">Search</button>
                        
                        <div class="Results-message" id="no-results-message" style="display:none;">No items found for "<span id="search-term"></span>".</div>
                        </div>
                       
                        <h3>Items List</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Discription</th>
                                    <th>Account Created At</th>
                                    <th>Account Updated At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="items-table-body">
                                    <?php foreach($items as $index => $item){ ?>
                                <tr>
                                    <td><?=$index+1?></td>
                                    <td><?=$item['item_name']?></td>
                                    <td><?=$item['price']?></td>
                                    <td><?=$item['quantity']?></td>
                                    <td><?=$item['item_description']?></td>
                                    <td><?=date('M d, y @ h:i:s A', strtotime($item['created_at']))?></td>
                                    <td><?=date('M d, y @ h:i:s A', strtotime($item['updated_at']))?></td>
                                    <td>
                                    <div class="action-buttons-container">
                                        <button class="action-button edit-button" onclick="openEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['first_name']) ?>', '<?= htmlspecialchars($user['last_name']) ?>', '<?= htmlspecialchars($user['email']) ?>')">
                                            <i class="fas fa-camera"></i> Edit
                                        </button>
                                        <a href="#" class="action-button delete-button" data-userid="<?=$user['id']?>" data-item-name="<?=$item['item_name']?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>

                                </tr>
                                    <?php } ?>
                            </tbody>

                    </div>
                
            </div>
        </div>
    </div>

</body>

<script>
function searchItems() {
    var input = document.getElementById("search-input").value.toLowerCase();
    var tableBody = document.getElementById("items-table-body");
    var rows = tableBody.getElementsByTagName("tr");
    var noResultsMessage = document.getElementById("no-results-message");
    var searchTerm = document.getElementById("search-term");

    var hasResults = false;

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName("td");
        if (cells.length > 0) {
            var itemName = cells[1].textContent.toLowerCase();
            if (itemName.includes(input) || input.trim() === "") {
                rows[i].style.display = ""; // Show matching row
                hasResults = true; // There are results
            } else {
                rows[i].style.display = "none"; // Hide non-matching row
            }
        }
    }

    // Display no results message if no matches found
    if (!hasResults && input.trim() !== "") {
        searchTerm.textContent = input; // Set the search term
        noResultsMessage.style.display = "block"; // Show message
    } else {
        noResultsMessage.style.display = "none"; // Hide message if there are results
    }
}
</script>
</html>