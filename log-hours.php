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
    $sessionEmpId = $_SESSION["id"];
    $list = new View();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST["date"]; /* Mandatory field */
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
            if ($entries->checkDayEntry($date, $projectId, $taskTypeId, $taskName, $description, $hours, $expenseTypeId, $expenseAmount, $sessionEmpId)) {
                header("Location: ./entry-logged.php");
            }
            else {
                $error_date = date("jS \of F Y", strtotime($date));
                $error = "You have already created an entry for ".$error_date.". You can edit it in the view timesheet section if it has not been submitted yet.";
            }
        }
        else {
            $error = "Please give a valid project name";
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
        <link rel="stylesheet" href="./css/log-hours.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./index.php"><i class="fas fa-arrow-left"></i></a>
                <h1>Log hours by day</h1>
                <?php
                    if (isset($error)) {
                        echo "<p class='error-message'>".$error."</p>";
                    }
                    else {
                        echo "<p>Log the hours the you have worked for here.</p>";
                    }
                ?>
                <p><b>*</b> = required fields, <b>?</b> = special condition.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <label for="date"><b>Date*</b></label>
                    <input type="date" name="date" min="2021-01-01" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>" required>
                    <label for="project"><b>Project Name*</b></label>
                    <input list="project" name="project" value="<?php echo isset($_POST['project']) ? $_POST['project'] : '' ?>" required>
                    <datalist id="project">
                        <?php
                            $list->printProjects();
                        ?>
                    </datalist>
                    <label for="task-type"><b>Task Type*</b></label>
                    <input list="task-type" name="task-type" value="<?php echo isset($_POST['task-type']) ? $_POST['task-type'] : '' ?>" required>
                    <datalist id="task-type">
                        <?php
                            $list->printTaskTypes($sessionEmpId);
                        ?>
                    </datalist>
                    <label for="task-name"><b>Task Name</b></label>
                    <input type="text" name="task-name" value="<?php echo isset($_POST['task-name']) ? $_POST['task-name'] : '' ?>"> 
                    <label for="task-description"><b>Task Description</b></label>
                    <textarea for="task-description" name="task-description" rows="3"><?php echo isset($_POST['task-description']) ? $_POST['task-description'] : '' ?></textarea>
                    <label for="hours"><b>Hours Worked*</b></label>
                    <input type="number" name="hours" min="0" max="24" value="<?php echo isset($_POST['hours']) ? $_POST['hours'] : '' ?>" required>
                    <label for="expense-type">
                        <b>Expense Type
                            <div>?
                                <span class="hover-text">If an expense type is given, an expennse amount is required</span>
                            </div>
                        </b>
                    </label>
                    <input list="expense-type" name="expense-type" value="<?php echo isset($_POST['expense-type']) ? $_POST['expense-type'] : '' ?>">
                    <datalist id="expense-type">
                        <?php
                            $list->printExpenseTypes($sessionEmpId);
                        ?>
                    </datalist>
                    <label for="expense-amount"><b>Expense Amount</b></label>
                    <input type="number" name="expense-amount" min="0" value="<?php echo isset($_POST['expense-amount']) ? $_POST['expense-amount'] : '' ?>">
                    <div class="button-container">
                        <button type="submit">Submit</button>
                        <button type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>