<?php
class View extends Entries {
    // Show all the entries from the timesheet table
    public function printTimesheetEntries($filters) {
        $data = $this->getAllTimesheets($filters);
        if (is_null($data)) {
            echo("<div class = 'timesheet-no-results'>");
            echo "<h0>No Weekly Timesheets Found</h0>";
            echo "</div>";
        } else {
            // echo("<script>console.log('PHP: " . 3 . "');</script>");
            foreach ($data as $entries) {
                echo "<div class='timesheet-container' onclick='viewDayEntries(".$entries['Id'].")'>";
                if ($_SESSION['type'] == "manager") {
                    echo "<h2>First Name: ".$entries['firstName']."</h1>";
                    echo "<h2>Last Name: ".$entries['lastName']."</h1>";
                    echo('<br>');
                }
                echo "<h2>Start: ".$entries['start']."</h2>";
                echo "<h2>End: ".$entries['end']."</h2>";
                echo "<p>Status: ".$entries['status']."</p>";
                if ($entries["submitted"] == NULL) {
                    echo "<p>Date Submitted: N/A</p>";
                }
                else {
                    echo "<p>Date Submitted: ".$entries['submitted']."</p>";
                }
                if ($entries["resolved"] == NULL) {
                    echo "<p>Date Resolved: N/A</p>";
                }
                else {
                    echo "<p>Date Resolved: ".$entries['resolved']."</p>";
                }
                // submit (+ delete) weekly timesheet button (implemented as a form with hidden value of timesheet id)
                $timesheetId = $entries['Id'];
                // echo "<form action='submit-weekly-timesheet.php' method='post'>
                // <input type='hidden' name='timesheetId' value='$timesheetId'/>
                // <button type='submit' formaction='submit-weekly-timesheet.php'>Submit</button>
                // <button type = 'submit' formaction='delete-weekly-timesheet.php'>Delete</button>
                // </form>";
                if ($entries["status"] == "In-progress" || $entries["status"] == "Disapproved" && $entries["empId"] == $_SESSION['id']) {
                    echo "<form action='submit-weekly-timesheet.php' method='post'>
                    <input type='hidden' name='timesheetId' value='$timesheetId'/>
                    <button type='submit' formaction='submit-weekly-timesheet.php'>Submit</button>
                    <button type='submit' formaction='delete-weekly-timesheet.php'>Delete</button>
                    </form>";
                } else if ($_SESSION['type'] == "manager" && $entries["status"] == "In-review") {
                    echo "<form action='approve-weekly-timesheet.php' method='post'>
                    <input type='hidden' name='timesheetId' value='$timesheetId'/>
                    <button type='submit' name='approval' value='Approved'>Approve</button>
                    <button type='submit' name='approval' value='Disapproved'>Disapporve</button>
                    </form>";
                }
                // USE FOR EXAMPLE ($_POST['Action'] == 'submit') {
                echo "</div>";
            }
        }
    }

    public function printDayEntries($timesheetID, $filters) {
        // echo("<script>console.log('PHP: " . 3 . "');</script>");
        // echo("<script>console.log('PHP: " . $timesheetID . "');</script>");
        $data = $this->getAllDayEntries($timesheetID, $filters);
        if (is_null($data)) {
            echo("<div class = 'timesheet-no-results'>");
            echo "<h0>No Day Entries Found</h0>";
            echo "</div>";
        } else {
            foreach ($data as $entries) {
                echo "<div class='timesheet-container'>";
                if ($_SESSION['type'] == "manager") {
                    echo "<h2>First Name: ".$entries['firstName']."</h1>";
                    echo "<h2>Last Name: ".$entries['lastName']."</h1>";
                    echo('<br>');
                }
                echo "<h2>Date: ".date("d-m-Y", strtotime($entries['date']))."</h2>";
                echo "<h2>Project: ".$entries['projectName']."</h2>";
                echo "<p>Hours: ".$entries['hours']."</p>";
                echo "<p>Task: ".$entries['taskType']."</p>";
                echo "<p>Expense Type: ".$entries['expenseType']."</p>";
                echo "<p>Expense Amount: ".$entries['expenseAmount']."</p>";
                // submit (+ delete) weekly timesheet button (implemented as a form with hidden value of timesheet id)
                $dayEntryId = $entries['Id'];
                echo("<script>console.log('PHP : " .  $dayEntryId . "');</script>");
                if (($entries["status"] == "In-progress" || $entries["status"] == "Disapproved") && $entries['empId'] == $_SESSION['id']) {
                    echo "<form action='edit-entries.php' method='post'>
                            <input type='hidden' name='day-entry-id' value='$dayEntryId'/>
                            <button type='submit' name='action' value='edit'>Edit</button>
                            <button type='submit' name='action' value='delete'>Delete</button>
                        </form>";
                }
                echo "</div>";
            }
        }
    }

    public function printAccountDetails() {
        $data = $this->getAccountDetails();
        foreach ($data as $entries) {
            echo "<div class='timesheet-container'>";
            echo "<h2>Account Number: ".$entries['accNumber']."</h2>";
            echo "<p>Username: ".$entries['username']."</p>";
            echo "<p>First Name: ".$entries['firstName']."</p>";
            echo "<p>Surname: ".$entries['lastName']."</p>";
            echo "<p>Date of Birth: ".$entries['dateofbirth']."</p>";
            echo "<p>Status: ".$entries['status']."</p>";
            echo "<p>Employee ID: ".$entries['empId']."</p>";
            echo "</div>";
        }
    }

    public function printProjects() {
        $data = $this->getProjects();
        if ($data != NULL) {
            foreach ($data as $entries) {
                echo "<option value='$entries[projectName]'>";
            }
        }
    }

    public function printTaskTypes($sessionEmpId) {
        $data = $this->getTaskTypes($sessionEmpId);
        if ($data != NULL) {
            foreach ($data as $entries) {
                echo "<option value='$entries[taskType]'>";
            }
        } else {
            echo("<script>console.log('PHP: error getting task types');</script>");
        }
    }

    public function printExpenseTypes($sessionEmpId) {
        $data = $this->getExpenseTypes($sessionEmpId);
        if ($data != NULL) {
            foreach ($data as $entries) {
                echo "<option value='$entries[expenseType]'>";
            }
        }
    }

    public function printProjectName($dayEntryId) {
        $data = $this->getProjectName($dayEntryId);
        if ($data != NULL) {
            foreach ($data as $entries) {
                echo $entries['projectName'];
            }
        }
    }

    public function printDateOfEntry($dayEntryId) {
        $data = $this->getDateOfEntry($dayEntryId);
        if ($data != NULL) {
            foreach ($data as $entries) {
                echo $entries['date'];
            }
        }
    }
}
?>