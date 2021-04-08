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
}
?>