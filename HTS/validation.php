<?php
session_start();
// A Connection is made to the local server and to the "hts" database.
$conn = new mysqli("localhost", "root", "", "hts");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// The details given by the user are checked to see if a match exists within the table.
$username = $_POST["username"];
$password = $_POST["password"];
$sql = "SELECT ID, Username, Password, Type FROM users";
$result = $conn->query($sql);

// Go through each entry in the table and see if a match exists.
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if ($row["Username"] == $username && $row["Password"] == $password) {
      // Session status and id are used to show that a user is logged in and to keep track of which user has logged in respectively.
      $_SESSION["status"] = true;
      $_SESSION["id"] = $row["ID"];
      $_SESSION["type"] = $row["Type"];
      if ($row["Type"] == "consultant" || $row["Type"] == "manager" || $row["Type"] == "administrator" || $row["Type"] == "finance") { 
        header("Location: index.php"); 
      }
    }
  }
}
else {
  echo "ERROR - There seems to be an issue with the database. No login details found";
}
$conn->close();
?>