<?php
// USED TO OBTAIN MONDAY'S DATE OF A GIVEN DATE.

$date = $_POST["date"];
echo(getMondayDate($date));
echo "<br/>";
echo(getSundayDate($date));

function getMondayDate($date) {
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

function compensateNegativeDay($monday, $month) {
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

function getSundayDate($date) {
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
?>