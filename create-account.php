<?php 
session_start();
if (isset($_SESSION['users'])) {header( 'location: create-account.php');
    exit();
}
$user = $_SESSION['users'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" type="text/css" href="./public/createAccount.css">
    <link rel="stylesheet" type="text/css" href="./public/nav.css">
</head>
<body>
   <!-- Navigation bar -->
   <?php include('partials/navbar.php')?>

    <div class="create-account-container">
        <h2>Create Account</h2>
        <form class="create-account-form" action="database/add.php" method="POST" onsubmit="return validatePasswordMatch()"> 
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
                    $is_success = $_SESSION['response']['sucess'];
                 ?>

             <div class="error-message" id="error-message">
             <p class="error_message <?= $is_success ? '
                error_message_True' : 'error_message_False' ?>" >
                    <?= $responseMessage?>
            </p>
                    </div>
            <?php unset($_SESSION['response']); } ?>
    </div>

    <script>
        function validatePasswordMatch() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>

</body>
</html>
