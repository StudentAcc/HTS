<?php
class SubmitHours extends Connect { 
    public function checkDayEntry($date, $projectId, $taskTypeId, $taskName, $description, $hours, $expenseTypeId, $expenseAmount, $sessionEmpId) {
        // Check to see which timesheet the daily entries needs to be linked to.
        $timesheetId = $this->checkTimesheet($date, $sessionEmpId);
        // Check if the given date already matches the date of another day entry.
        $sql = "SELECT d.date, w.consultantId FROM DayEntries d INNER JOIN WeeklyTimesheets w
        ON d.date = '$date' AND w.consultantId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // There is an existing day entry with the same date.
            // THERE ALREADY EXISTS A DAILY ENTRY OF USING THE INPUTTED DATE!
            return false;
        }
        else {
            // Creates a new day entry.
            $dayEntryId = $this->createDayEntry($date, $timesheetId, $sessionEmpId);
            if ($expenseTypeId != NULL) {
                $this->createExpense($expenseTypeId, $expenseAmount, $dayEntryId);
            }
            // Create a new task entry that connects to the newly created day entry via foreign key reference.
            $this->createTask($projectId, $taskTypeId, $taskName, $description, $hours, $dayEntryId);
            return true;
        }
    }

    private function checkTimesheet($date, $sessionEmpId) {
        $sql = "SELECT Id, start, end, consultantId FROM WeeklyTimesheets";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($date >= date($row["start"]) && $date <= date($row["end"]) && $sessionEmpId == $row["consultantId"]) {
                    return $row["Id"];
                }
            }
        }
        // Day entry doesn't have a timesheet to link to, so a new timesheet is created to link the day entry with it.
        return $this->createTimesheet($date, $sessionEmpId, "SELECT Id, start, end, consultantId FROM WeeklyTimesheets");
    }

    private function createTimesheet($date, $sessionEmpId, $check_query) {
        $monday = $this->getMondayDate($date);
        $sunday = $this->getSundayDate($date);
        $sql = "INSERT INTO WeeklyTimesheets (status, start, end, consultantId) VALUES ('In-progress', '$monday', '$sunday', '$sessionEmpId')";
        if ($this->connect()->query($sql) === TRUE) { // Again, check to see which timesheet the daily entries needs to be linked to.
            $check_timesheet = $this->connect()->query($check_query);
            if ($check_timesheet->num_rows > 0) {
                while ($row = $check_timesheet->fetch_assoc()) {
                    if ($date >= date($row["start"]) && $date <= date($row["end"]) && $sessionEmpId == $row["consultantId"]) {
                        return $row["Id"];
                    }
                }
            }
        }
        return NULL;
    }

    private function createDayEntry($date, $timesheetId, $sessionEmpId) {
        // Insert a new daily entry into the database.
        $sql = "INSERT INTO DayEntries (date, weeklyTimesheetID) VALUES ('$date', '$timesheetId')";
        if ($this->connect()->query($sql) === TRUE) { // Again, check to see which timesheet the daily entries needs to be linked to.
            $check_query = "SELECT d.Id FROM DayEntries d, WeeklyTimesheets w
            WHERE d.date = '$date' AND d.weeklyTimesheetId = w.Id AND w.consultantId = '$sessionEmpId'";
            $check_dayEntry = $this->connect()->query($check_query);
            if ($check_dayEntry->num_rows > 0) { // Should only return 1 row.
                while ($row = $check_dayEntry->fetch_assoc()) {
                    return $row["Id"];
                }
            }
        }
        return NULL;
    }

    private function createTask($projectId, $taskTypeid, $taskName, $description, $hours, $dayEntryId) {
        $sql = "INSERT INTO Task (projectId, taskTypeId, taskName, taskDescription, hours, dayEntryId)
        VALUES ('$projectId', '$taskTypeid', '$taskName', '$description', '$hours', '$dayEntryId')";
        $this->connect()->query($sql);
    }

    private function createExpense($expenseTypeId, $expenseAmount, $dayEntryId) {
        $sql = "INSERT INTO Expense (expenseTypeId, expenseAmount, dayEntryId) VALUES ('$expenseTypeId', '$expenseAmount', '$dayEntryId')";
        $this->connect()->query($sql);
    }


    public function checkTaskList($taskType, $sessionEmpId) {
        // Check if the personalised task type exists.
        $sql = "SELECT t.Id, t.taskType, c.empId FROM TaskTypeList t INNER JOIN ConsultantsTaskTypes c
        ON t.Id = c.taskTypeId AND c.empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) { // There exists personalised tasks types. 
            while ($row = $result->fetch_assoc()) {
                if ($taskType == $row["taskType"]) {
                    return $row["Id"];
                }
            }
        }
        // If no match is found, check to see if the task type is present in the task type list table.
        // If so, create the link. Otherwise, insert the task type into the task type table and then make the link.
        $taskTypeId = $this->checkTaskTypeListRows($taskType, 0);
        $this->checkConsultantTaskTypeRows($taskTypeId, $sessionEmpId);
        return $taskTypeId;
    }

    private function checkTaskTypeListRows($taskType, $counter) {
        $sql = "SELECT Id, taskType FROM TaskTypeList";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($taskType == $row["taskType"]) {
                    return $row["Id"];
                }
            }
        }
        if ($counter == 0) {
            // If no match is found, the given task type doesn't exist in the database. Thus, create a new row.
            $this->createTaskTypeListRow($taskType);
            return $this->checkTaskTypeListRows($taskType, $counter++);
        }
    }

    private function createTaskTypeListRow($taskType) {
        $sql = "INSERT INTO TaskTypeList (taskType) VALUES ('$taskType')";
        $this->connect()->query($sql);
    }

    private function checkConsultantTaskTypeRows($taskTypeId, $sessionEmpId) {
        $sql = "SELECT taskTypeId, empId FROM ConsultantsTaskTypes WHERE taskTypeId = '$taskTypeId' AND empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows != 1) { // If link doesn't exists, create the link.
            $this->createConsultantTaskType($taskTypeId, $sessionEmpId);
        }
    }

    private function createConsultantTaskType($taskTypeId, $sessionEmpId) {
        $sql = "INSERT INTO ConsultantsTaskTypes (taskTypeId, empId) VALUES ('$taskTypeId', '$sessionEmpId')";
        $this->connect()->query($sql);
    }

    public function checkExpenseList($expenseType, $sessionEmpId) {
        // Check if the personalised expense type exists.
        $sql = "SELECT e.Id, e.expenseType, c.empId FROM ExpenseTypeList e INNER JOIN ConsultantsExpenseTypes c
        ON e.Id = c.expenseTypeId AND c.empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) { // There exists personalised expense types. 
            while ($row = $result->fetch_assoc()) {
                if ($expenseType == $row["expenseType"]) {
                    return $row["Id"];
                }
            }
        }
        // If no match is found, check to see if the expense type is present in the expense type list table.
        // If so, create the link. Otherwise, insert the expnse type into the task type table and then make the link.
        $expenseTypeId = $this->checkExpenseTypeListRows($expenseType, 0);
        $this->checkConsultantExpenseTypeRows($expenseTypeId, $sessionEmpId);
        return $expenseTypeId;
    }

    private function checkExpenseTypeListRows($expenseType, $counter) {
        $sql = "SELECT Id, expenseType FROM ExpenseTypeList";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($expenseType == $row["expenseType"]) {
                    return $row["Id"];
                }
            }
        }
        if ($counter == 0) {
            // If no match is found, the given expense type doesn't exist in the database. Thus, create a new row.
            $this->createExpenseTypeListRow($expenseType);
            return $this->checkExpenseTypeListRows($expenseType, $counter++);
        }
    }

    private function createExpenseTypeListRow($expenseType) {
        $sql = "INSERT INTO ExpenseTypeList (expenseType) VALUES ('$expenseType')";
        $this->connect()->query($sql);
    }

    private function checkConsultantExpenseTypeRows($expenseTypeId, $sessionEmpId) {
        $sql = "SELECT expenseTypeId, empId FROM ConsultantsExpenseTypes WHERE expenseTypeId = '$expenseTypeId' AND empId = '$sessionEmpId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows != 1) { // If link doesn't exists, create the link.
            $this->createConsultantExpenseType($expenseTypeId, $sessionEmpId);
        }
    }

    private function createConsultantExpenseType($expenseTypeId, $sessionEmpId) {
        $sql = "INSERT INTO ConsultantsExpenseTypes (expenseTypeId, empId) VALUES ('$expenseTypeId', '$sessionEmpId')";
        $this->connect()->query($sql);
    }


    public function checkProjectList($project) {
        $sql = "SELECT Id, projectName FROM ProjectList";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($project == $row["projectName"]) {
                    return $row["Id"];
                }
            }
        }
        return NULL;
    }

    /* ----------------------------------------------------------------------------------------- */
    /* ---------------------------------- EDIT HOURS FUNCTIONS --------------------------------- */
    /* ----------------------------------------------------------------------------------------- */

    public function editDayEntry($dayEntryId, $projectId, $taskTypeId, $taskName, $description, $hours, $expenseTypeId, $expenseAmount) {
        // The task row and expense row that is specific to the day entry are updated.
        $this->updateTask($dayEntryId, $projectId, $taskTypeId, $taskName, $description, $hours);
        $this->updateExpense($dayEntryId, $expenseTypeId, $expenseAmount);
    }

    private function updateTask($dayEntryId, $projectId, $taskTypeId, $taskName, $description, $hours) {
        $sql = "UPDATE Task SET projectId = '$projectId', taskTypeId = '$taskTypeId', taskName = '$taskName', taskDescription = '$description', hours = '$hours' WHERE dayEntryId = '$dayEntryId'";
        $result = $this->connect()->query($sql);
    }

    private function updateExpense($dayEntryId, $expenseTypeId, $expenseAmount) {
        $sql = "UPDATE Expense SET expenseType = '$expenseTypeId', expenseAmount = '$expenseAmount' WHERE dayEntryId = '$dayEntryId'";

    }

    /* ----------------------------------------------------------------------------------------- */
    /* ---------------- DATE FUNCTIONS FOR OBTAINING MONDAY'S AND SUNDAY'S DATE ---------------- */
    /* ----------------------------------------------------------------------------------------- */
    private function getMondayDate($date) {
        $monday = date("d", strtotime($date));
        $dayNumber = date("w", strtotime($date));
        $month = date("n", strtotime($date));
        $year = date("Y", strtotime($date));
        $counter = 0;
        while ($dayNumber != 1) {
            if ($dayNumber == 0) {
                $counter--;
                $dayNumber = 6;
            }
            else {
                $counter--;
                $dayNumber--;
            }
        }
        $monday += $counter;
        // After the subtraction, $monday could be either 0 or a negative, we check to see if a compensation needs to be made.
        // If so, go to the previous month and check what the new value of $monday should be from the compensate function.
        // Another compensation needs to be made (if necessary), so that the month will be a valid number.
        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
            if ($monday <= 0) {
                $month--;
                if ($month == 0) {
                    $year--;
                    $month = 12;
                }
                $monday = compensateNegativeDay($monday, $month);
            }
        }
        elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
            if ($monday <= 0) {
                $month--;
                if ($month == 0) {
                    $year--;
                    $month = 12;
                }
                $monday = compensateNegativeDay($monday, $month);
            }
        }
        elseif ($month == 2) {
            // Check if the given date is a leap year
            if (date("L", strtotime($date))) {
                if ($monday <= 0) {
                    $month--;
                    if ($month == 0) {
                        $year--;
                        $month = 12;
                    }
                    $monday = compensateNegativeDay($monday, $month);
                }
            }
            else {
                if ($monday <= 0) {
                    $month--;
                    if ($month == 0) {
                        $year--;
                        $month = 12;
                    }
                    $monday = compensateNegativeDay($monday, $month);
                }
            }
        }
        // Put 0 in front of month and/or day, if necessary.
        if ($month < 10 && $monday < 10) {
            return $year."-0".$month."-0".$monday;    
        }
        elseif ($month < 10) {
            return $year."-0".$month."-".$monday;    
        }
        elseif ($monday < 10) {
            return $year."-".$month."-0".$monday;    
        }
        else {
            return $year."-".$month."-".$monday;
        }
    }
    
    private function compensateNegativeDay($monday, $month) {
        // The moonth that was previously changed is now used to determine what the last day of the month will be,
        // which will be added to the negative value of $monday (which gives us the correct monday date).
        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
            $monday += 31;
        }
        elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
            $monday += 30;
        }
        elseif ($month == 2) {
            if (date("L", strtotime($date))) {
                $monday += 29;
            }
            else {
                $monday += 28;
            }
        }
        return $monday;
    }
    
    private function getSundayDate($date) {
        $sunday = date("d", strtotime($date));
        $dayNumber = date("w", strtotime($date));
        $month = date("n", strtotime($date));
        $year = date("Y", strtotime($date));
        $counter = 0;
        while ($dayNumber != 0) {
            if ($dayNumber == 6) {
                $counter++;
                $dayNumber = 0;
            }
            else {
                $counter++;
                $dayNumber++;
            }
        }
        $sunday += $counter;
        // From the previous addition, sunday can have the value bigger than the last day of the month.
        // Therefore , we check to see if $sunday is bigger than last day of the month.
        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) {
            if ($sunday > 31) {
                $month++;
                $sunday -= 31;
            }
        }
        elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
            if ($sunday > 30) {
                $month++;
                $sunday -= 30;
            }
        }
        elseif ($month == 2) {
            // Check if the date given is a leap year.
            if (date("L", strtotime($date))) {
                if ($sunday > 29) {
                    $month++;
                    $sunday -= 29;
                }
            }
            else {
                if ($sunday > 28) {
                    $month++;
                    $sunday -= 28;
                }
            }
        }
        // Check $month if its bigger than 12.
        if ($month > 12) {
            $year++;
            $month = 1;
        }
        // Put 0 in front of month and/or day, if necessary.
        if ($month < 10 && $sunday < 10) {
            return $year."-0".$month."-0".$sunday;    
        }
        elseif ($month < 10) {
            return $year."-0".$month."-".$sunday;    
        }
        elseif ($sunday < 10) {
            return $year."-".$month."-0".$sunday;    
        }
        else {
            return $year."-".$month."-".$sunday;
        }
    }
}
?>