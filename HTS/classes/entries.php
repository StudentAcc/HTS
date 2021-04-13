<?php
class Entries extends Connect { 
    // Returns the entries from the timesheet table.
    protected function getAllTimesheets() {
        $sql = "SELECT * FROM WeeklyTimesheets";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($_SESSION["id"] == $row["consultantId"]) {
                    $entries[] = $row;
                }
            }
            return $entries;
        }
    }
    
    // Returns the entries from the projectList (contains a list of projects) table.
    protected function getProjects() {
        $sql = "SELECT * FROM ProjectList";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    // Returns the entries that are specific to a user from the tasks (contains a list of tasks) table.
    protected function getTaskTypes($sessionEmpId) {
        $sql = "SELECT t.Id, t.taskType FROM ConsultantsTaskTypes c INNER JOIN TaskTypeList t
        ON c.taskTypeId = t.id AND c.empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getExpenseTypes($sessionEmpId) {
        $sql = "SELECT e.Id, e.expenseType FROM ConsultantsExpenseTypes c INNER JOIN ExpenseTypeList e
        ON c.expenseTypeId = e.Id AND empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getAllDayEntries($timesheetID, $filters) {
        $temp = $_SESSION['id'];
        $date = $filters['Date']."%";

        $hours = $filters['Hours'];
        $task = $filters['Task'];
        echo("<script>console.log('PHP: " . 3 . "');</script>");
        echo("<script>console.log('PHP : " . $date . "');</script>");
        $sql = "SELECT * FROM dayentries d INNER JOIN weeklytimesheets w ON d.WeeklyTimesheetId = w.Id AND w.consultantId = '$temp' 
        INNER JOIN task t ON t.dayEntryID = d.Id INNER JOIN projectlist p ON p.Id = t.projectId INNER JOIN tasktypelist tl ON tl.Id = t.taskTypeId
        INNER JOIN expense e ON e.dayEntryId = d.Id INNER JOIN expensetypelist el ON el.Id = e.expenseTypeId 
        WHERE d.date LIKE '$date' AND t.hours LIKE '$hours' AND tl.taskType LIKE '$task'
        ORDER BY d.date DESC";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                 $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getProjectName($dayEntryId) {
        $sql = "SELECT p.projectName FROM ProjectList p, Task t WHERE t.dayEntryId = '$dayEntryId' AND t.projectId = p.Id";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Project name does exist
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getDateOfEntry($dayEntryId) {
        $sql = "SELECT d.date FROM DayEntries d WHERE Id = '$dayEntryId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Project name does exist
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }
}
?>