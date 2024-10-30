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

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://kit.fontawesome.com/57b929fbcb.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./CSS/sidebar.css">
        <link rel="stylesheet" href="./public/admin.css">
        <title>Document</title>
    </head>

    <body>
          <!-- Side bar -->
        <?php include('partials/sidebar.php')?>

        <div class="main-content">
        <div class="dashboard_topbar">
                <a><i class="fa fa-navicon"></i></a>
                <a>  <h2>Admin User Management</h2></a>
                <a href="./database/logout.php" class="logout"><i class="fa fa-power-off"></i>Logout</a>
            </div>
          
            <div class="content-placeholder">
            <!-- Add user -->
             <div class="create-account-container">
                        <h2>Add Account</h2>
                        <form class="create-account-form" action="database/adminAdd.php" method="POST" onsubmit="return validatePasswordMatch()"> 
                            <input type="text" name="first_name" placeholder="First Name" id="first_name"required/>
                            <input type="text" name="last_name" placeholder="Last Name" id="last_name" required/>
                            <input type="email" name="email" placeholder="Email" id="email" required/>
                            <input type="password" name="password" placeholder="Password" id="password"required/>
                            <input type="password" name="confirm-password" placeholder="Confirm Password"  id="confirm-password"required/>
                            <input type="submit" value="Create Account"/>
                        </form>
                        <?php
                            if(isset($_SESSION['response'])) { 
                                $responseMessage = $_SESSION['response']['message'];
                                    $is_success = $_SESSION['response']['success'];
                                ?>

                            <div class="error-message" id="error-message">
                                <p class="error_message <?= $is_success ? '
                                    error_message_True' : 'error_message_False' ?>" >
                                        <?= $responseMessage?>
                                </p>
                            </div>
                            <?php unset($_SESSION['response']); } ?>
             </div> 

             <!-- Display User list -->
             <div class="account-container">
                <h3>Account List</h3>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Account Created At</th>
                            <th>Account Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $index => $user){ ?>
                            <tr>
                             <td><?=$index+1?></td>
                             <td><?=$user['first_name']?></td>
                             <td><?=$user['last_name']?></td>
                             <td><?=$user['email']?></td>
                             <td><?=date('M d, y @ h:i:s A', strtotime($user['created_at']))?></td>
                             <td><?=date('M d, y @ h:i:s A', strtotime($user['updated_at']))?></td>
                             <td>
                             <button class="action-button edit-button" onclick="openEditModal(<?= $user['id'] ?>, '<?= htmlspecialchars($user['first_name']) ?>', '<?= htmlspecialchars($user['last_name']) ?>', '<?= htmlspecialchars($user['email']) ?>')">
                                    <i class="fas fa-camera"></i> Edit
                                </button> 
                             <a href="#" class="action-button delete-button" data-userid="<?=$user['id']?>" data-fname="<?=$user['first_name']?>" \
                                 data-lname = "<?=$user['last_name']?>">
                                <i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                            <?php } ?>
                           
                    </tbody>

            </div>

            <!-- Edit User Modal -->
                <div id="editUserModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" onclick="closeEditModal()">&times;</span>
                        <h2>Edit User</h2>
                        <form id="editUserForm" action="database/update-user.php" method="POST">
                            <input type="hidden" name="user_id" id="modal_user_id" />
                            <label for="modal_first_name">First Name:</label>
                            <input type="text" name="f_name" id="modal_first_name" required />
                            <label for="modal_last_name">Last Name:</label>
                            <input type="text" name="l_name" id="modal_last_name" required />
                            <label for="modal_email">Email:</label>
                            <input type="email" name="email" id="modal_email" required />
                            <input type="submit" value="Update User" />
                        </form>
                    </div>
                </div>


        </div>
          
        <script>src="js/jquery/min.js" </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>src="js/script.js"</script>
        <script>
                // Function to validate password and confirm password match
                function validatePasswordMatch() {
                    const password = document.getElementById("password").value;
                    const confirmPassword = document.getElementById("confirm-password").value;
                    
                    if (password === "" || confirmPassword === "") {
                        alert("Please fill out both password fields!");
                        return false;
                    }
                    
                    if (password !== confirmPassword) {
                        alert("Passwords do not match!");
                        return false; // Prevent form submission
                    }
                    return true; // Allow form submission
                }

                //Edit and Delete function 
                function script(){
                    
                    this.initilize = function(){
                        this.registerEvents();
                    }, 
                    this.registerEvents= function(){
                        document.addEventListener('click',function(e){ 
                            targetElement = e.target;
                            classList = targetElement.classList;

                            if(classList.contains('delete-button')){
                                e.preventDefault();
                                userId = targetElement.dataset.userid;
                                fname = targetElement.dataset.fname;
                                lname = targetElement.dataset.lname;
                                fullname = fname +' '+ lname;                                
                                if(window.confirm("Are you sure to delete user: "+fullname+ "!!!")){
                                    $.ajax({
                                        method:'POST',
                                        data: {
                                            user_id: userId ,
                                            f_name: fname,
                                            l_name: lname   
                                        },
                                        url: 'database/delete-user.php',
                                        dataType: 'json',
                                        success: function(data){
                                            if(data.success){
                                                if(window.confirm(data.message)){
                                                    location.reload();
                                                  
                                                }
                                            }else window.alert(data.message);
                                        }
                                    })
                                    
                                }else{
                                    console.log("Delete cancelled!!");
                                }
                            }
                           
                        });
                    }


                }
                var script = new script;
                script.initilize();
                
                // Open the Edit User Modal
                function openEditModal(id, firstName, lastName, email) {
                    document.getElementById('modal_user_id').value = id;
                    document.getElementById('modal_first_name').value = firstName;
                    document.getElementById('modal_last_name').value = lastName;
                    document.getElementById('modal_email').value = email;

                    document.getElementById('editUserModal').style.display = 'block';
                }

                // Close the Edit User Modal
                function closeEditModal() {
                    document.getElementById('editUserModal').style.display = 'none';
                }

                // Close modal when clicking outside of it
                window.onclick = function(event) {
                    const modal = document.getElementById('editUserModal');
                    if (event.target == modal) {
                        closeEditModal();
                    }
                }

        </script>
    
    </body>
</html>