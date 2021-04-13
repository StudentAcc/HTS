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
                echo "<form action='submit-weekly-timesheet.php' method='post'>
                        <input type='hidden' name='timesheetId' value='$timesheetId'/>
                        <button type='submit'>Submit</button>
                        <button type = 'submit'>Delete</button>
                    </form>
                </div>";
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
                echo "<h2>Date: ".date("d-m-Y", strtotime($entries['date']))."</h2>";
                echo "<h2>Hours: ".$entries['hours']."</h2>";
                echo "<p>Task: ".$entries['taskType']."</p>";
                echo "<p>Project: ".$entries['projectName']."</p>";
                echo "<p>Expense Type: ".$entries['expenseType']."</p>";
                echo "<p>Expense Amount: ".$entries['expenseAmount']."</p>";
                echo "</div>";
            }
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
}
?>