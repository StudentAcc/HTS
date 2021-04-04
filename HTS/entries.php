<?php
class Entries extends Connect {
    // Returns the entries from the timesheet table.
    protected function getAllTimesheets() {
        $sql = "SELECT * FROM WeeklyTimesheets";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($_SESSION["id"] == $row["ConsultantID"]) {
                    $entries[] = $row;
                }
            }
            return $entries;
        }
    }

    //Return the entries from the loghours (daily entries) table.
}
?>
