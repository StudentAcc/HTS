<?php
    require "./classes/connect.php";
    require "./classes/approve.php";
    session_start();
    // Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
    if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
        header("Location: ./login.php");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' and $_SESSION['type'] == "manager") {
        $approve = new Approve();
        echo("<script>console.log('PHP: " . $_POST['approval'] . "');</script>");
        if ($_POST['approval'] == 'Approved') {
            if($approve->approvalTimesheet($_POST["timesheetId"], $_POST['approval'])) {
                header("Location: ./timesheet-approved.php");
            } else {
                echo("<script>console.log('PHP: " . 1 . "');</script>");
                echo "Invalid timesheet approved";
            }
        } else if ($_POST['approval'] == 'Disapproved') {
            if($approve->approvalTimesheet($_POST["timesheetId"], $_POST['approval'])){
                header("Location: ./timesheet-disapproved.php");
            } else {
                echo("<script>console.log('PHP: " . 2 . "');</script>");
                echo "Invalid timesheet Disapproved";
            }
        } else {
            echo("<script>console.log('PHP: " . 3 . "');</script>");
            echo "Invalid approval status";
        }
    }
?>