<?php
    session_start();
    require "./connect.php";
    require "./entries.php";
    require "./view.php";
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
                <h1>View Timesheets</h1>
                <?php
                    $timesheets = new View();
                    $timesheets->printTimesheetEntries();
                ?>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>