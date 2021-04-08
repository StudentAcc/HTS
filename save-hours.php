<?php
session_start();
// A Connection is made to the local server and to the "hts" database.
$conn = new mysqli("localhost", "root", "", "hts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
	//but consultant can insert more than one task though?
		//figure out how to implement that^? maybe each task is in its own form
			//OR just don't implement multiple tasks 
					
//get values submitted from submitted form 

//mandatory form fields: 
$date = $_POST["date"];
$project = $_POST["project"];// don't need client name as the project name is associated with client in database (see projectList table) when they pick one from the list which the user can't see 
$taskType = $_POST["task-type"]; //change this name in html form
//$taskTypeId = $_POST["task-type-id"];	//hidden value of task type id 
$hours = $_POST["hours"];

//optional form fields:
if(isset($_POST["task-name"])){
	$taskName = $_POST["task-name"];
}
if(isset($_POST["task-description"])){
	$taskDescription = $_POST["task-description"];
}
if(isset($_POST["expense-type"])){ //in html form, if expense type is chosen the expense amount field is mandatroy to be filled
	$expenseType = $_POST["expense-type"];
}
if(isset($_POST["expense-amount"])){
	$expenseAmount = $_POST["expense-amount"];
}

// Insert values into the tables it belongs in
	/*since its an OOP database, have to insert values into the tables with primary keys that 
	are referenced in other tables first, then get the id of that primary key that was auto-incremented
	(we dk what the id is at first since we didn't manually  insert it), then we insert that 
	primary id into the table that references it into the collumn that stores the foreign key.
	-Durra
 	*/
 /***************INITIAL CREATION OF DAY ENTRY*****************/
  //Insert date  in dayEntry first to create it 
  //rest of values are NULL as other values need to be inserted in other tables first (e.g. expense)
  //after rest of values are found, it is updated into the day entry 
  //NOTE: have to set default of all values except for idas null in table phpmyadmin
  	//for some reason mine isn't working 
 	mysqli_query($conn,"INSERT INTO DayEntries(date)
		VALUES ('$date', $weeklyTimesheet)")
	or die("Query unsuccessful " . mysqli_error($conn));
	
		//get the autoincremented id of the day entry just inserted in table
	//here is a website that might help:
		//https://dev.mysql.com/doc/connector-odbc/en/connector-odbc-usagenotes-functionality-last-insert-id.html
	$dayEntryId = "query result here";
	
//INSERTION OF WEEKLY TIMESHEET ID: PUT YASIR'S EXISTING CODE HERE !!!

	//if weekly timesheet hasn't been created{
		//create weekly timesheet
		$weeklyTimesheetId = "(Yasir's code)";
		//updates the day entry that was just created (using the day entry id)
		mysqli_query($conn,"UPDATE DayEntries 
		SET (weeklyTimesheetId = '$weeklyTimesheetId') 
		WHERE Id ='$dayEntryId'") //}
	
	//if weekly timesheet has already been created
		//find the weekly timesheet id
		$weeklyTimesheetId = "(Yasir's code)";
			//insert it into the day entry 
			mysqli_query($conn,"UPDATE DayEntries 
 			SET (weeklyTimesheetId = '$weeklyTimesheetId') 
			WHERE Id ='$dayEntryId'")
			
/***********************************************************/
 
/***************CREATION OF OPTIONAL EXPENSE*****************/	
	if(isset($_POST["expense-type"]) && isset($_POST["expense-amount"])){
 		//QUERY HERE 
			//pseudo query: select id from expenseTypeList where expenseType = '$expenseType'
			//have to put return the result (which should only be one have to do if row ===1
		$expenseTypeId = "result of the query";
		
		mysqli_query($conn,"INSERT INTO Expense(expenseTypeId, expenseAmount)
			VALUES ($expenseTypeId, $expenseAmount)")
		or die("Query unsuccessful " . mysqli_error($conn));
		
		//get the autoincremented id of the expense just inserted in table
		//here is a website that might help:
			//https://dev.mysql.com/doc/connector-odbc/en/connector-odbc-usagenotes-functionality-last-insert-id.html
			$expenseId = "query result here";
	} 
/***********************************************************/ 	

		
 	// if expense is filled then insert it in the day entry with the day entry id already created
 	if(isset($_POST["expense-type"]) && isset($_POST["expense-amount"])){
 		mysqli_query($conn,"UPDATE DayEntries 
 		SET (expenseId = '$expenseId') 
		WHERE Id ='$dayEntryId'")
	or die("Query unsuccessful " . mysqli_error($conn));
 	}
 	
	//Find the project ID of project selected, taskTypeId or task type selected to be inserted into Task table
		//QUERY HERE 
			//pseudo query:select id from project where name = '$project'
		$projectId = "result of the query";
		//Find the task type ID of task selected
		//QUERY HERE 
			//pseudo query:select id from taskTypeList where taskType = '$taskType'
		$taskTypeId = "result of the query";
		
 	mysqli_query($conn,"INSERT INTO Task(projectId, taskTypeId, hours,dayEntryId)
		VALUES ('$projectId', '$taskTypeId' , '$hours', ;$dayEntryId;)")
	or die("Query unsuccessful " . mysqli_error($conn));
	
	//get the autoincremented id of the task entry just inserted in table
		//here is a website that might help:
			//https://dev.mysql.com/doc/connector-odbc/en/connector-odbc-usagenotes-functionality-last-insert-id.html
		$taskId = "query result here";
	
	//if task name  is filled, update task name in the row with the task id
	
	//if task description is filled , update task name in the row with the task id
	
	//instead of INSERT has to be update to existing row!
	

//goes back to log day entries page after successful insertion of values
    header("Location: log-hours.php");

$conn->close();
?>