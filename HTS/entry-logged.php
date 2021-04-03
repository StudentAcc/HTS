<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href="./css/entry-logged.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <div class="container">
                <a href="./log-hours.php"><i class="fas fa-arrow-left"></i></a>
                <h1>Your Daily Entry Has Been Successfully Logged Into Your Records</h1>
                <div class="button-container">
                    <a href="./log-hours.php">Log Again</a>
                    <a href="./index.php">Main Page</a>
                </div>
            </div>
        </section>
    </body>
    <script src="index.js"></script>
</html>