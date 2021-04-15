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
            "Week" => emptyCheck($_POST["Week"],"%"),
            // "End" => emptyCheck($_POST["End"],"%"),
            "Status"  => emptyCheck($_POST["Status"],"%"),
            "Submitted"  => emptyCheck($_POST["Submitted"],"%"),
            "Resolved"  => emptyCheck($_POST["Resolved"],"%")
        ];
        if ($_SESSION['type'] == "manager") {
            $filters["Id"] = emptyCheck($_POST["Id"],"%");
            $filters["Firstname"] = emptyCheck($_POST["Firstname"],"%");
            $filters["Lastname"] = emptyCheck($_POST["Lastname"],"%");
        } else {
            $filters["Id"]  = "%";
            $filters["Firstname"]  = "%";
            $filters["Lastname"]  = "%";
        }
    } else {
        $filters = [
            "Id"  => "%",
            "Firstname"  => "%",
            "Lastname"  => "%",
            "Week" => "%",
            // "End"  => "%",
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
                    echo('<form id = "ViewTimesheetFilter" class="filter-form" method="post" action="./view-timesheets.php">');
                    if ($_SESSION["type"] == "manager") {
                        echo('<div class="filter-field-and-label">');
                        echo('<label for="Id"><b>ID: </b></label>');
                        echo('<input name="Id" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Id"]:"").'">');
                        echo('</div>');
                        echo('<div class="filter-field-and-label">');
                        echo('<label for="Firstname"><b>First Name: </b></label>');
                        echo('<input name="Firstname" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Firstname"]:"").'">');
                        echo('</div>');
                        echo('<div class="filter-field-and-label">');
                        echo('<label for="Lastname"><b>Last Name: </b></label>');
                        echo('<input name="Lastname" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Lastname"]:"").'">');
                        echo('</div>');
                    }
                    echo('<div class="filter-field-and-label">');
                    echo('<label for="Week"><b>Week: </b></label>');
                    echo('<input name="Week" type="week" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Week"]:"").'">');
                    echo('</div>');
                    // echo('<label for="End"><b>End</b></label>');
                    // echo('<input name="End" type="month" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["End"]:"").'">');
                    echo('<div class="filter-field-and-label">');
                    echo('<label for="Status"><b>Status: </b></label>');
                    echo('<input list = "status-list" name="Status" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Status"]:"").'">');
                    echo('<datalist id="status-list">');
                    echo('<option value="In-progress">');
                    echo('<option value="In-review">');
                    echo('<option value="Approved">');
                    echo('<option value="Disapproved">');
                    echo('</datalist>');
                    echo('</div>');
                    echo('<div class="filter-field-and-label">');
                    echo('<label for="Submitted"><b>Submitted: </b></label>');
                    echo('<input name="Submitted" type="month" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Submitted"]:"").'">');
                    echo('</div>');
                    echo('<div class="filter-field-and-label">');
                    echo('<label for="Resolved"><b>Resolved: </b></label>');
                    echo('<input name="Resolved" type="month" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Resolved"]:"").'">');
                    echo('</div>');
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