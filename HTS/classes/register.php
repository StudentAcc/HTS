<?php
class Register extends Connect {
    // For the sign-up, used to check whether the credentials (username and email) provided are unique or already in use.
    public function checkCredentials($username, $email) {
        $sql = "SELECT * FROM Account a, Employee e WHERE a.empID = e.empID";
        $result = $this->connect()->query($sql);
        $credentialsExist = false;
        // Check if credientials don't already exist, give error message if it does.
        if($result->num_rows > 0) {  //if table has row(s)
            while($row = $result->fetch_assoc()) {
                if($credentialsExist == false) {
                    if($username === $row['username'] || $email === $row['email']) { // Username or email already exists in database
                        $credentialsExist = true;
                    }
                }
            } //end while loop
        }
        return $credentialsExist;
    }

    public function checkManagerDetails($firstName, $lastName, $company, $email) {
        $sql = "SELECT m.empId, e.firstname, e.lastname, m.company, e.email FROM  Employee e, Manager m WHERE m.empID = e.empID";
        $result = $this->connect()->query($sql);
        $managerMatch = false;
        // Check if the credintials don't already exist, give error message if it does.
        if ($result->num_rows > 0) { // check if the details given match the existing manager details
            while ($row = $result->fetch_assoc()) {
                if ($firstName === $row['firstname'] && $lastName === $row['lastname'] &&  $company === $row['company'] && $email === $row['email']) {
                    $managerMatch = true;
                    $empId = $managerRow["empId"];
                    //insert values into Account table
                    $this->createAccount($username, $password, $status, $empId);
                    echo "Account created successfully<br/>";
                    //get the automatically generated acc number
                    $accNum = $this->getAccountNumber($empId);
                    echo "Obtained account number<br/>";
                    //insert $accNumber and update email setting values into specific managerAccount email settings info
                    $this->updateEmail($accNum);
                    echo "<p>Sign up successful. You can now <a href='login.php'>login</a>.</p>";
                }
            }
        }
        return $managerMatch;
    }

    private function createAccount($username, $password, $status, $empId) {
        $sql = "INSERT INTO Account (username, password, status, empID) VALUES ('$username','$password', '$status', '$empId')";
        $this->connect()->query($sql);
    }

    private function updateEmail($accNum) {
        $sql = "INSERT INTO ManagerAccount (accNumber, emailUpdateStatus) VALUES ('$accNum',true)";
        $this->connect()->query($sql);
    }

    private function getAccountNumber($empId) {
        $sql = "SELECT a.accNumber FROM Account a WHERE a.empID = '$empId'";
        $result = $this->connect()->query($sql);
        if ($result->num_rows > 0) { // Entries exist in database, the query should output one row.
            while ($row = $result->fetch_assoc()) {
                echo $row['accNumber'];
                $accNum = $row['accNumber'];
            }
        }
        return $accNum;
    }
} 
?>