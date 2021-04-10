<?php
class View extends Entries {
    // Show all the entries from the timesheet table
    public function printTimesheetEntries() {
        $data = $this->getAllTimesheets();
        foreach ($data as $entries) {
            echo "<div class='timesheet-container'>";
            echo "<h2>Start: ".$entries['start']."</h2>";
            echo "<h2>End: ".$entries['end']."</h2>";
            echo "<p>Status: ".$entries['status']."</p>";
            if ($entries["submitted"] == NULL) {
                echo "<p>Date Submitted: N/A</p>";
            }
            else {
                echo "<p>Date Submitted: ".$entries['Submitted']."</p>";
            }
            if ($entries["resolved"] == NULL) {
                echo "<p>Date Resolved: N/A</p>";
            }
            else {
                echo "<p>Date Resolved: ".$entries['resolved']."</p>";
            }
            echo "</div>";
        }
    }

    public function printAccountDetails() {
        $data = $this->getAccountDetails();
        foreach ($data as $entries) {
            echo "<div class='timesheet-container'>";
            echo "<h2>Account Number: ".$entries['accNumber']."</h2>";
            echo "<p>Username: ".$entries['username']."</p>";
            echo "<p>First Name: ".$entries['firstname']."</p>";
            echo "<p>Surname: ".$entries['surname']."</p>";
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