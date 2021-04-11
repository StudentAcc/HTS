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

function viewLogHours(timesheetID) {
    window.location.href = "view-day-entries.php?id=" + timesheetID; 
}

function ToggleDisplayFilter (id, className, idPrefix) {
    console.log(id, className);
    if (document.getElementById(id).style.display == 'none' || document.getElementById(id).style.display == '') {
        document.getElementById(id).style.display = 'inline';
        var filters = document.getElementsByClassName(className);
        for (var i=0; i<filters.length; i++) {
            filters[i].addEventListener("input",FilterEventHandler);
        }
    } else {
        document.getElementById(id).style.display = 'none';
        var filters = document.getElementsByClassName(className);
        for (var i=0; i<filters.length; i++) {
            filters[i].value = "";
            filters[i].removeEventListener("input",FilterEventHandler);
        }
        document.querySelectorAll('[id^="'+idPrefix+'"]').forEach((entry) => {
            // console.log(entry);
            entry.style.display = 'block';
        });
    }
}

function FilterEventHandler() {
    // if (document.getElementById('DayEntryFilter-Month').style.display == 'inline') {
    console.log(this.className)
    entryColectionName = this.id.split('-')[0];
    document.querySelectorAll('[id^="'+entryColectionName+'_"]').forEach((entry) => {
        entry.style.display = 'none';
    });

    var filterString = entryColectionName
    var filters = document.getElementsByClassName(this.className);
    for (var i=0; i<filters.length; i++) {
        filterString += '_' + filters[i].getAttribute("name") + "-" + filters[i].value
    }

    if (filterString.slice(-1) != "-") {
        filterString += '_'
    }

    // Month = String("DayEntryID" + document.getElementById("DayEntryFilter-Month").value);
    document.querySelectorAll('[id*= '+filterString+']').forEach((entry) => {
        entry.style.display = 'block';
    });

    // }
}