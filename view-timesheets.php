<?php
    require "./classes/connect.php";
    require "./classes/entries.php";
    require "./classes/view.php";
    session_start();
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location: ./login.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filters = [
            "Start"   => emptyCheck($_POST["Start"],"%"),
            "End"  => emptyCheck($_POST["End"],"%"),
            "Status"  => emptyCheck($_POST["Status"],"%"),
            "Submitted"  => emptyCheck($_POST["Submitted"],"%"),
            "Resolved"  => emptyCheck($_POST["Resolved"],"%")
        ];
    } else {
        $filters = [
            "Start"   => "%",
            "End"  => "%",
            "Status"  => "%",
            "Submitted"  => "%",
            "Resolved"  => "%"
        ];
    }

    function emptyCheck($stringToCheck, $stringToConvertTo) {
        echo("<script>console.log('PHP: " . $stringToCheck . "');</script>");
        if($stringToCheck == "") {
            echo("<script>console.log('PHP: 1');</script>");
            return $stringToConvertTo;
        } else {
            echo("<script>console.log('PHP: 2');</script>");
            return $stringToCheck;
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
                    echo('<aside class = "Filters">');
                    echo('<button id = "Reset Filters" onClick="viewTimesheetsFunction()">Reset Filters</button>');
                    echo('<form id = "ViewTimesheetFilter" method="post" action="./view-timesheets.php">');
                    echo('<label for="Start"><b>Start</b></label>');
                    echo('<input name="Start" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Start"]:"").'">');
                    echo('<label for="End"><b>End</b></label>');
                    echo('<input name="End" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["End"]:"").'">');
                    echo('<label for="Status"><b>Status</b></label>');
                    echo('<input name="Status" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Status"]:"").'">');
                    echo('<label for="Submitted"><b>Submitted</b></label>');
                    echo('<input name="Submitted" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Submitted"]:"").'">');
                    echo('<label for="Resolved"><b>Resolved</b></label>');
                    echo('<input name="Resolved" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Resolved"]:"").'">');
                    echo('<button type="submit">Submit</button>');
                    echo('</form>');
                    echo('</aside>');
                    $timesheets = new View();
                    $timesheets->printTimesheetEntries($filters);
                ?>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>