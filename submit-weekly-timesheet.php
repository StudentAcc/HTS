<?php
    require "./classes/connect.php";
    require "./classes/submit.php";
    session_start();
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location: ./login.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $timesheetId = $_POST["timesheetId"];
        $submit = new Submit();
        $submit->submitTimesheet($timesheetId);
        header("Location: ./timesheet-submitted.php");
    }
?>