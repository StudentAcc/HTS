<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hourglass Timesheet System</title>
        <link rel="stylesheet" href="./css/reset.css">
        <link rel="stylesheet" href="./css/navbar.css">
        <link rel="stylesheet" href="./css/index.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css">
    </head>
    <body>
        <?php include "./navbar.php";?>
        <section>
            <?php
            // type session variable determines what is to be displayed on the page.
            if ($_SESSION["type"] == "consultant") {
                include "consultant.php";
            }
            elseif ($_SESSION["type"] == "manager") {
                include "manager.php";
            }
            elseif ($_SESSION["type"] == "administrator") {
                include "administrator.php";
            }
            elseif ($_SESSION["type"] == "finance") {
                include "finance.php";
            }
            ?>
        </section>
    </body>
    <script src="index.js"></script>
</html>