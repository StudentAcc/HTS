<?php
    require "./classes/connect.php";
    require "./classes/validation.php";

    session_start();
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location:  ./login.php");
     }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $empId = $_POST["empId"];
         }
        else {
            $error = "Account does not exist.";
        }

?>

<DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href="./css/search-employees.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
      <?php include "./navbar.php"; ?>
      <section>
          <div class="container">
              <a href="./index.php"><i class="fas fa-arrow-left"></i></a>
              <h1>Search Accounts</h1>
              <p>Search for accounts here.</p>
              <form action="./viewAccount.php" method="post">
                  <label for="empId"><b>Employee ID</b></label>
                  <input type="text" name="empId" required>
                  <button type="submit">Submit</button>
              </form>
              <?php
                if ($_SESSION["type"] == "administrator") {
                    echo "<a class='search-employees' href='./searchEmployees.php'>Search by employee name</a>";
                }
              ?>
          </div>
      </section>
    </body>
    <script src="index.js"></script>
</html>
