<?php
class Connect {
  // A Connection is made to the local server and to the "hts" database.
  function connect() {
    $conn = new mysqli("localhost", "root", "", "hts");
    return $conn;
  }
}
?>