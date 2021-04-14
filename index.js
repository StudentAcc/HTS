function displayList() {
    // Clicking on the bars icon will display the contents of the list and changes the bars icon into a X icon.
    // Clicking on the X icon will hide the contents of the list and changes the X icon back into the bars icon.
    let button = document.getElementById("dropdown-button")
    let element = [
        document.getElementById("account-details"),
        document.getElementById("sign-in")];
    for (i = 0; i < 2; i++) {
        if (element[i].className == "secondary-li-active"){
            element[i].className = "secondary-li-inactive";
        }
        else {
            element[i].className = "secondary-li-active";
        }
    }
    if (button.className == "fas fa-bars fa-2x") {
        button.className = "fas fa-times fa-2x";
    }
    else {
        button.className = "fas fa-bars fa-2x"
    }
}

function logoutFunction() {
    window.location.replace("logout.php");
}

function logHoursFunction() {
    window.location.replace("log-hours.php");
}

function viewTimesheetsFunction() {
    window.location.replace("view-timesheets.php");
}

function viewDayEntries(timesheetID) {
    window.location.href = "view-day-entries.php?id=" + timesheetID; 
}

function searchDayEntriesFunction(){
    window.location.replace("search-day-entries.php");
}

function searchEmployeesFunction() {
    window.location.href = "searchEmployees.php";
}

function searchAccountsFunction() {
    window.location.href = "searchAccounts.php";
}