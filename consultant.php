<?php
echo "<div class='row'>
<div class='column'>
    <div class='card' onclick='logHoursFunction()'>
        <img src='./images/Log_Hours.png' alt='Log Hours'>
        <div class='container'>
            <h3>Log Hours by Day</h3>
            <p>You can manually log the hours that you have worked for here</p>  
        </div>
    </div>
</div>
//Durra- ADD NEW ICON SAYING Search Day Entries !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//Durra- Change view timesheet h3 headings on all files to View Weekly Timesheets
<div class='column'>
    <div class='card' onClick='viewTimesheetsFunction()'>
        <img src='.//images/View_Timesheets.png' alt='View Timesheets'>
        <div class='container'>
            <h3>View Timesheets</h3>
            <p>View your timesheets, make changes and/or submit your timesheets to get approval here</p>  
        </div>
    </div>
</div>
<div class='column'>
    <div class='card' onclick='searchEmployeesFunction()'>
        <img src='.//images/Search_Employees.png' alt='Search Employees'>
        <div class='container'>
            <h3>Search Employees</h3>
            <p>Look up for your fellow employees here</p>  
        </div>
    </div>
</div>
</div>"
?>