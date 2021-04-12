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
            "Task"  => emptyCheck($_POST["Task"],"%")
        ];
    } else {
        $_SESSION["timesheetID"] = $_GET["id"];
        $filters = [
            "Date"   => "%",
            "Hours"  => "%",
            "Task"  => "%"
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
                    echo('<button type="submit">Submit</button>');
                    echo('</form>');

                    echo("<script>console.log('PHP: " . $filters['Date'] . "');</script>");
                    echo("<script>console.log('PHP: " . $filters['Hours'] . "');</script>");
                    echo("<script>console.log('PHP: " . $filters['Task'] . "');</script>");

                    // echo('<div class = "HiddenAdjacentDropdown" id = "HiddenAdjacentDropdown">');
                    // echo('<input name="Date" type="Month" class = "HiddenAdjacentDropdownSelection" id="DayEntry-Filter-Month">');
                    // echo('<input name="Hours" type="number" class = "HiddenAdjacentDropdownSelection" id="DayEntry-Filter-Hours">');
                    // // echo('<input name="Task" type="text" class = "HiddenAdjacentDropdownSelection" id="DayEntry-Filter-Task">');
                    // // echo('<input name="Project" type="text" class = "HiddenAdjacentDropdownSelection" id="DayEntry-Filter-Project">');
                    // echo('</div>');
                    echo('</aside>');
                    $dayEntries = new View();
                    $dayEntries->printDayEntries($_SESSION["timesheetID"], $filters);
                ?>
                <!-- <form method="post" action="./view-day-entries.php">
                <label for="Month"><b>Month</b></label>
                <input name="Date" type="Month">
                <label for="Hours"><b>Hours</b></label>
                <input name="Hours" type="number">
                <label for="Task"><b>Task</b></label>
                <input name="Task" type="text">
                <button type="submit">Submit</button>
                </form> -->
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>