<?php
require "./classes/connect.php";
require "./classes/entries.php";
require "./classes/view.php";
require "./classes/submit-hours.php";
session_start();
// Check if the user is logged in. The user shouldn't have access to this page, if they haven't logged in.
if(!isset($_SESSION["type"])) { // Redirect the user to the login page, if the user hasn't logged in yet.
    header("Location: ./login.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Date is not required
    $dayEntryId = $_POST["day-entry-id"];
    $project = $_POST["project"]; /* Mandatory field */
    $taskType = $_POST["task-type"]; /* Mandatory field */
    if (empty($_POST["task-name"])) { /* Optional field */
        $taskName = NULL;
    }
    else {
        $taskName = $_POST["task-name"];
    }
    if (empty($POST["description"])) { /* Optional field */
        $description = NULL;
    }
    else {
        $description = $_POST["task-description"];    
    }
    $hours = $_POST["hours"]; /* Mandatory field */
    // If an expense type is not given, the value of expense amount will alwas be 0, regardless of the input.
    if (empty($_POST["expense-type"])) {
        $expenseType = NULL;
        $expenseAmount = 0;
    }
    else {
        $expenseType = $_POST["expense-type"];
        $expenseAmount = $_POST["expense-amount"];
    }
    $entries = new SubmitHours();
    // Check to see if a valid project name was given.
    $projectId = $entries->checkProjectList($project);
    // Check to see if the given task type exists. If not, create task type list row that links to the newly created day entry.
    $taskTypeId = $entries->checkTaskList($taskType, $sessionEmpId);
    // Check whether an expense type was given. If an expense type was given,
    // then check to see if the given expense type exists in the table.
    // If not, create task type list row that links to the newly created day entry.
    if ($expenseType != NULL) {
        $expenseTypeId = $entries->checkExpenseList($expenseType, $sessionEmpId);
    }
    else {
        $expenseTypeId = NULL;
    }
    if ($projectId != NULL) {
        $entries->editDayEntry($dayEntryId, $projectId, $taskTypeId, $taskName, $description, $hours, $expenseTypeId, $expenseAmount);
        header("Location: ./editted-entry-logged.php");
    }
}
?>