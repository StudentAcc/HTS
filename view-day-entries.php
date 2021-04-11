<?php
    require "./classes/connect.php";
    require "./classes/entries.php";
    require "./classes/view.php";
    // $div = new DOMDocument(); 
    // $timesheetID = $div->getElementById('dayEntryTemp')->nodeValue;;
    // echo("<script>console.log('PHP: " . 3 . "');</script>");
    // echo("<script>console.log('PHP: " . $timesheetID . "');</script>");
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
        <link rel="stylesheet" href="./css/view-day-entries.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./view-timesheets.php"><i class="fas fa-arrow-left"></i></a>
                <h1>View Day Entries</h1>
                <?php
                    $timesheetID = $_GET["id"];
                    echo("<script>console.log('PHP: " . 3 . "');</script>");
                    echo("<script>console.log('PHP: " . $timesheetID . "');</script>");
                    $dayEntries = new View();
                    $dayEntries->printDayEntries($timesheetID);
                ?>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>