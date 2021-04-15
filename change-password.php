<?php
    require "./classes/connect.php";
    require "./classes/entries.php";
    require "./classes/view.php";
    require "./classes/validation.php";
    require "./classes/change-details.php";
    session_start();
    
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location: ./login.php");
    }
    $error = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST["username"];
	    $password = $_POST["password"];
        $new_password1 = $_POST["new_password1"];
        $new_password2 = $_POST["new_password2"];
        $checkValidity = new Validation();
        if ($checkValidity->login($username, $password)) {
            if ($new_password1 == $new_password2){
                $test = new ChangeDetails();
                $test->changePassword($username, $password, $new_password1);
                $error = "Password changed successfully!";
            }
            else {
                $error = "Your new passwords don't match.";
            }
        }
        else {
            $error = "Your login credentials are incorrect.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href="./css/account.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./account-details.php"><i class="fas fa-arrow-left"></i></a>
                <h1>Change Password</h1>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" required>
                  
                    <label for="password"><b>Current Password</b></label>
                    <input type = "password" name = "password"  minlength = "7" required>
                    <p></p>
                    <br>
                    <label for="password"><b>New Password</b></label>
        
                    <input type = "password" name = "new_password1" placeholder = "Minimum 7 characters"  minlength = "7" required>
                    
                    <label for="password"><b>New Password</b></label>
                    <input type = "password" name = "new_password2" placeholder = "Minimum 7 characters"  minlength = "7" required>
                    <br>
                    <br>
                    <button type="submit">Change Password</button>
                </form>
                <br>
                <p><?php echo $error; ?></p>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>
