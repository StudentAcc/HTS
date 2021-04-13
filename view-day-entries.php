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

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $filters = [
            "Date"   => emptyCheck($_POST["Date"],"%"),
            "Hours"  => emptyCheck($_POST["Hours"],"%"),
            "Task"  => emptyCheck($_POST["Task"],"%"),
            "Project"  => emptyCheck($_POST["Project"],"%"),
            "ExpenseType"  => emptyCheck($_POST["ExpenseType"],"%"),
            "ExpenseAmount"  => emptyCheck($_POST["ExpenseAmount"],"%")
        ];
    } else {
        $_SESSION["timesheetID"] = $_GET["id"];
        $filters = [
            "Date"   => "%",
            "Hours"  => "%",
            "Task"  => "%",
            "Project"  => "%",
            "ExpenseType"  => "%",
            "ExpenseAmount"  => "%"
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
        <link rel="stylesheet" href="./css/view-day-entries.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./view-timesheets.php"><i class="fas fa-arrow-left"></i></a>
                <h1>View Day Entries</h1>
                <?php
                    // echo("<script>console.log('PHP: " . 3 . "');</script>");
                    // echo("<script>console.log('PHP: " . $timesheetID . "');</script>");
                    echo('<aside class = "Filters">');
                    echo('<button id = "Reset Filters" onClick="viewLogHours('.$_SESSION["timesheetID"].')">Reset Filters</button>');
                    echo('<form id = "DayEntryFilter" method="post" action="./view-day-entries.php">');
                    echo('<label for="Date"><b>Date</b></label>');
                    echo('<input name="Date" type="Month" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Date"]:"").'">');
                    echo('<label for="Hours"><b>Hours</b></label>');
                    echo('<input name="Hours" type="number" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Hours"]:"").'">');
                    echo('<label for="Task"><b>Task</b></label>');
                    echo('<input name="Task" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Task"]:"").'">');
                    echo('<label for="Project"><b>Project</b></label>');
                    echo('<input name="Project" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["Project"]:"").'">');
                    echo('<label for="ExpenseType"><b>Expense Type</b></label>');
                    echo('<input name="ExpenseType" type="text" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["ExpenseType"]:"").'">');
                    echo('<label for="ExpenseAmount"><b>Expense Amount</b></label>');
                    echo('<input name="ExpenseAmount" type="number" value="'.($_SERVER["REQUEST_METHOD"] == "POST"? $_POST["ExpenseAmount"]:"").'">');
                    echo('<button type="submit">Submit</button>');
                    echo('</form>');

                    // echo("<script>console.log('PHP: " . $filters['Date'] . "');</script>");
                    // echo("<script>console.log('PHP: " . $filters['Hours'] . "');</script>");
                    // echo("<script>console.log('PHP: " . $filters['Task'] . "');</script>");

                    echo('</aside>');
                    $dayEntries = new View();
                    $dayEntries->printDayEntries($_SESSION["timesheetID"], $filters);
                ?>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>