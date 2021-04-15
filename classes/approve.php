<?php
class Approve extends Connect {

    private function changeTimesheetStatus($timesheetId, $approveStatus) {
        $resolvedOn = date("Y-m-d");
        $sql = "UPDATE WeeklyTimesheets w SET w.status = '$approveStatus'".($approveStatus == "Approved"?", w.resolved = '$resolvedOn'":"")." WHERE w.Id = '$timesheetId'";
        $result = $this->connect()->query($sql);
    }

    public function approvalTimesheet($timesheetId,$approveStatus) {
        // First delete the task and enpense rows associated to each day entry of the selected timesheet.
        $timesheetId = $_POST["timesheetId"];
        $sql = "SELECT * FROM WeeklyTimesheets w WHERE w.Id = '$timesheetId' AND w.status = 'In-review'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            if ($approveStatus == "Approved") {
                $this->changeTimesheetStatus($timesheetId, $approveStatus);
                return true;
            } else if ($approveStatus == "Disapproved") {
                $this->changeTimesheetStatus($timesheetId, $approveStatus);
                return true;
            } else {
                echo "Invalid";
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}
?>