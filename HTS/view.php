<?php
class View extends Entries {
    // Show all the entries from the timesheet table
    public function printTimesheetEntries() {
        $data = $this->getAllTimesheets();
        foreach ($data as $entries) {
            echo "<div class='timesheet-container'>";
            echo "<h2>Start: ".$entries['Start']."</h2>";
            echo "<h2>End: ".$entries['End']."</h2>";
            echo "<p>Status: ".$entries['Status']."</p>";
            if ($entries["Submitted"] == NULL) {
                echo "<p>Date Submitted: N/A</p>";
            }
            else {
                echo "<p>Date Submitted: ".$entries['Submitted']."</p>";
            }
            if ($entries["Resolved"] == NULL) {
                echo "<p>Date Resolved: N/A</p>";
            }
            else {
                echo "<p>Date Resolved: ".$entries['Resolved']."</p>";
            }
            echo "</div>";
        }
    }
}
?>