<?php
    require "./classes/connect.php";
    require "./classes/entries.php";
    require "./classes/view.php";
    session_start();
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location: ./login.php");
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
        <link rel="stylesheet" href="./css/view-timesheets.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./index.php"><i class="fas fa-arrow-left"></i></a>
                <h1>View Weekly Timesheets</h1>
                <?php
                    $timesheets = new View();
                    $timesheets->printTimesheetEntries();
                ?>
            </div>
        </section>
        <p></p>
    </body>
    <script src="index.js"></script>
</html>