<?php
echo "<nav class='navbar'>
    <ul>
        <li><a href='./index.php'>HTS<i class='fas fa-hourglass-half'></i></a></li>
        <li class='dropdown-icon' onclick='displayList()'><i id='dropdown-button' class='fas fa-bars fa-2x'></i></li>
        <li id='account-details' class='secondary-li-inactive'><a href='./account-details.php'>Account Details</a></li>
        <li id='sign-in' class='secondary-li-inactive'><a href='./logout.php'>Sign Out</a></li>
    </ul>
    <button type='button' onclick='logoutFunction()'>Sign Out</button>
</nav>";
?>