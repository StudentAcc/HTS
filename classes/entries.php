<?php
class Entries extends Connect { 
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
        $sessionEmpId = ($_SESSION['type'] == "manager" ? "%" : $sessionEmpId);
        $sql = "SELECT t.Id, t.taskType FROM ConsultantsTaskTypes c INNER JOIN TaskTypeList t
        ON c.taskTypeId = t.id AND c.empId LIKE '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getExpenseTypes($sessionEmpId) {
        $sessionEmpId = ($_SESSION['type'] == "manager" ? "%" : $sessionEmpId);
        $sql = "SELECT e.Id, e.expenseType FROM ConsultantsExpenseTypes c INNER JOIN ExpenseTypeList e
        ON c.expenseTypeId = e.Id WHERE empId LIKE '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            return $entries;
        }
    }

    protected function getAllTimesheets($filters) {
        if ($_SESSION['type'] == "manager") {
            $temp = "%";
        } else {
            $temp = $_SESSION['id'];
        }
        $firstName = $filters['Firstname'];
        $lastName = $filters['Lastname'];
        echo("<script>console.log('PHP: " . $firstName . "');</script>");
        echo("<script>console.log('PHP: " . $lastName . "');</script>");
        $start = $filters['Start'];
        $end = $filters['End'];
        $status = $filters['Status'];
        $resolved = $filters['Resolved'];
        $submitted = $filters['Submitted'];
        $sql = "SELECT * FROM weeklytimesheets w INNER JOIN employee em ON w.consultantId = em.empId WHERE w.consultantId LIKE '$temp' AND w.start LIKE '$start' AND w.end LIKE '$end'
        AND w.status LIKE '$status' AND ( (w.submitted LIKE '$submitted') ".( $submitted == "%" ?'OR w.submitted IS NULL)':")")."AND em.firstName LIKE '$firstName' AND em.lastName LIKE '$lastName' AND 
        ( ( w.resolved LIKE '$resolved') ".( $resolved == "%" ?"OR w.resolved IS NULL)":")")."";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $timesheets[] = $row;
                echo("<script>console.log('PHP: " . 3 . "');</script>");
            }
            return $timesheets;
        }
    }

    protected function getAllDayEntries($timesheetID, $filters) {
        if ($_SESSION['type'] == "manager") {
            $temp = "%";
        } else {
            $temp = $_SESSION['id'];
        }
        $date = $filters['Date']."%";
        $hours = $filters['Hours'];
        $task = $filters['Task'];
        $project = $filters['Project'];
        $expenseType = $filters['ExpenseType'];
        $expenseAmount = $filters['ExpenseAmount'];
        // $W = "%";
        // echo("<script>console.log('PHP: " . 3 . "');</script>");
        // echo("<script>console.log('PHP : " .(($expenseType != "%") && ($expenseAmount != "%")?"gggggggggg":"AND el.expenseType LIKE AND e.expenseAmount LIKE") . "');</script>");
        $sql = "SELECT *, d.Id as 'Id' FROM dayentries d INNER JOIN weeklytimesheets w ON d.WeeklyTimesheetId = w.Id INNER JOIN employee em ON w.consultantId = em.empId INNER JOIN task t ON t.dayEntryID = d.Id INNER JOIN projectlist p ON p.Id = t.projectId INNER JOIN tasktypelist tl ON tl.Id = t.taskTypeId
        LEFT JOIN expense e ON e.dayEntryId = d.Id LEFT JOIN expensetypelist el ON el.Id = e.expenseTypeId
        WHERE w.consultantId LIKE '$temp' AND d.date LIKE '$date' AND t.hours LIKE '$hours' AND tl.taskType LIKE '$task' AND p.projectName LIKE '$project' 
        ".( $expenseType == "%" && $expenseAmount == "%"?"":"AND el.expenseType LIKE '$expenseType' AND e.expenseAmount LIKE '$expenseAmount' ")."AND w.Id = '$timesheetID' ORDER BY d.date DESC";
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