<?php
require "./classes/connect.php";
require "./classes/register.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST["firstname"];
	$lastName = $_POST["lastname"];
	$company = $_POST["company"];
	$email = $_POST["email"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$status = "active"; // initial status of new account is active 
    $checkValidity = new Register();
    // Username and email must always be unique. If so, then move to validate sign up details with existing fdm details of clients.	
    if ($checkValidity->checkCredentials($username, $email) === false) {
        // Check manager details against existing manager database.
        // If existing data matches, manager is automatically approved.
        if ($checkValidity->checkManagerDetails($firstName, $lastName, $company, $email) === true) {
            header('Location: ./sign-up-complete.php');
        }
        else {
            $error = "Sorry, looks like your details do not match FDMs existing client details.";
        }
    }
    else {
        $error = "Please use a unique username and email.";
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/start-navbar.css">
        <link rel="stylesheet" href="./css/sign-up.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./start-navbar.php";?>
        <section>
            <div class="sign-up">
                <a href="./login.php"><i class="fas fa-arrow-left"></i></a>
                <h1>Sign Up</h1>
                <?php
                    if (isset($error)) {
                        echo "<p class='error-message'>".$error."</p>";
                    }
                    else {
                        echo "<p>Sign up is for managers ONLY. Once signed up, please wait for the administrator to approve your request which will notify you via email.</p>";
                    }
                ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                	<label for="firstname"><b>First Name</b></label>
                    <input type="text" name="firstname" required>
                    <label for="lastname"><b>Last Name</b></label>
                    <input type="text" name="lastname" required>
                    <label for="company"><b>Company</b></label>
                    <input type="text" name="company" required>
                 	<label for="email"><b>Email</b></label>
                	<input type="email" name= "email" required>
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" required>
                    <label for="password"><b>Password</b></label>
                    <input type = "password" name = "password" placeholder = "Password: minimum 7 characters"  minlength = "7" required>
                    <button type="submit">Sign Up</button>
                </form>
            </div>
        </section>
    </body>
</html>