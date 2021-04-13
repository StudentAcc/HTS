<?php
    require "./classes/connect.php";
    require "./classes/validation.php";
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST["username"];
	    $password = $_POST["password"];
        $checkValidity = new Validation();
        if ($checkValidity->login($username, $password)) {
            header("Location: index.php");
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
        <link rel="stylesheet" href="./css/start-navbar.css">
        <link rel="stylesheet" href="./css/login.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php
        include "./start-navbar.php";
        // Check whether a user has already logged in.
        if (isset($_SESSION["type"])) {
            echo "<section>
                <div class='login'>
                    <h1>You are already logged in as ".$_SESSION["username"]."</h1>
                    <p>Go back to <a href='index.php'>homepage</a> or <a href='logout.php'>logout</a> to log into another account.</p>
                    <div class='button-container'>
                        <a href='./index.php'>Main Page</a>
                        <a href='./logout.php'>Sign out</a>
                    </div>
                </div>
            </section>";
        }
        else {
            echo "<section>
                <div class='login'>
                    <h1>Login</h1>".((isset($error)) ? '<p class="error-message">The credentials you entered are incorrect.</p>' : '<p>Welcome to hourglass timesheets system. Login into the System.</p>')."
                    <form action='".htmlspecialchars($_SERVER['PHP_SELF'])."' method='post'>
                        <label for='username'><b>Username</b></label>
                        <input type='text' name='username' required>
                        <label for='password'><b>Password</b></label>
                        <input type='password' name='password' required>
                        <button type='submit'>Login</button>
                    </form>
                    <div class='sign-up-container'>
                        <p>Don't have an account? <a href='sign-up.php'>Sign up!</a></p>
                    </div>
                </div>
            </section>";
        }?>
    </body>
</html>