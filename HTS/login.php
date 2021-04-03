<?php
session_start();
// Check whether a user has already logged in.
if (isset($_SESSION["status"]) == false) {
  $_SESSION["status"] = false;
  $_SESSION["id"] = 0;
  $_SESSION["type"] = "";
}
else {
    // A user has already typed in their credential. Determine which user it was via their ID.
    $conn = new mysqli("localhost", "root", "", "hts");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT ID, Type FROM users";
    $result = $conn->query($sql);
    // Go through each entry in the table and see which user already logged in via ID.
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row["ID"] == $_SESSION["id"]) {
                if ($row["Type"] == "consultant" || $row["Type"] == "manager" || $row["Type"] == "administrator" || $row["Type"] == "finance") { 
                    header("Location: index.php"); 
                }
            }
        }
    }
    else {
        echo "ERROR - There seems to be an issue with the database. No login details found";
    }
    $conn->close();
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
        <nav class="navbar">
            <ul>
                <li>HTS<i class="fas fa-hourglass-half"></i></li>
            </ul>
        </nav>
        <section>
            <div class="login">
                <h1>Login</h1>
                <form action="./validation.php" method="post">
                    <label for="username"><b>Username</b></label>
                    <input type="text" name="username" required>
                    <label for="password"><b>Password</b></label>
                    <input type="password" name="password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </section>
    </body>
</html>