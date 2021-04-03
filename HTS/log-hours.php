<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href="./css/log-hours.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./index.php"><i class="fas fa-arrow-left"></i></a>
                <h1>Log hours by day</h1>
                <form action="./submit-hours.php" method="post">
                    <label for="date"><b>Date</b></label>
                    <input type="date" name="date" required>
                    <label for="hours"><b>Hours Worked</b></label>
                    <input type="number" name="hours" min="0" max="24" required>
                    <label for="task"><b>Task Type</b></label>
                    <input list="task" name="task" required>
                    <datalist id="task">
                        <option value="Option 1">
                        <option value="Option 2">
                        <option value="Option 3">
                    </datalist>
                    <label for="project"><b>Project Name</b></label>
                    <input list="project" name="project" required>
                    <datalist id="project">
                        <option value="Option 1">
                        <option value="Option 2">
                        <option value="Option 3">
                    </datalist>
                    <label for="expense-type"><b>Expense Type</b></label>
                    <input list="expense-type" name="expense-type">
                    <datalist id="expense-type">
                        <option value="Option 1">
                        <option value="Option 2">
                        <option value="Option 3">
                    </datalist>
                    <label for="expense-amount"><b>Expense Amount</b></label>
                    <input type="number" name="expense-amount" min="0">
                    <div class="button-container">
                        <button type="submit">Submit</button>
                        <button type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>