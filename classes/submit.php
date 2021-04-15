<?php
class Submit extends Connect {
    public function submitTimesheet($timesheetId) {
        $submittedOn = date("Y-m-d");
        $sql = "UPDATE WeeklyTimesheets SET status = 'In-review', submitted = '$submittedOn' WHERE Id = '$timesheetId'";
        $result = $this->connect()->query($sql);
    }

    public function deleteTimesheet($timesheetId) {
        // First delete the task and enpense rows associated to each day entry of the selected timesheet.
        $timesheetId = $_POST["timesheetId"];
        $sql = "SELECT * FROM WeeklyTimesheets w WHERE w.Id = '$timesheetId' AND w.status = 'In-progress'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            $dayEntryId = $this->getDayEntryId($timesheetId);
            return true;
        } else {
            return false;
        }
    }

    private function getDayEntryId($timesheetId) {
        $sql = "SELECT d.Id FROM DayEntries d, WeeklyTimesheets w WHERE d.weeklyTimesheetId = '$timesheetId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->deleteTask($row["Id"]);
                $this->deleteExpense($row["Id"]);
                $this->deleteDayEntry($row["Id"]);
            }
        }
        $this->deleteWeeklyTimesheet($timesheetId);
    }

    private function deleteTask($dayEntryId) {
        $sql = "DELETE FROM Task WHERE dayEntryId = '$dayEntryId'";
        if ($result = $this->connect()->query($sql) === TRUE) {
            echo "Task row successfully deleted</br>";
        }
    }

    private function deleteExpense($dayEntryId) {
        $sql = "DELETE FROM Expense WHERE dayEntryId = '$dayEntryId'";
        if ($result = $this->connect()->query($sql) === TRUE) {
            echo "Task row successfully deleted</br>";
        }
        else {
            echo "No expense row has been deleted, as there wasn't one in the first place.</br>";
        }
    }

    private function deleteDayEntry($dayEntryId) {
        $sql = "DELETE FROM DayEntries WHERE Id = '$dayEntryId'";
        if ($result = $this->connect()->query($sql) === TRUE) {
            echo "Day entry row successfully deleted</br>";
        }
    }

    private function deleteWeeklyTimesheet($timesheetId) {
        $sql = "DELETE FROM WeeklyTimesheets WHERE Id = '$timesheetId'";
        if ($result = $this->connect()->query($sql) === TRUE) {
            echo "Timesheet row successfully deleted</br>";
        }
    }
}
?>