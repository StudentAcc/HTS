<?php
class Validation extends Connect {
    // Check against the database that the inputs the user provided are legitimate.
    public function login($username, $password) {
        $sql = "SELECT * FROM Account a,Employee e WHERE a.empId = e.empId";
        $result = $this->connect()->query($sql);
        // Check if credientials do exist.
        if($result->num_rows > 0) {  // If table has row(s)
            while($row = $result->fetch_assoc()) {
                if ($row["username"] == $username && $row["password"] == $password) { // Credentials match with database.
                    if ($row["status"] = "active") {
                        // Session status,id and username are used to show that a user is logged in and to keep track of which user has logged in respectively.
                        $_SESSION["status"] = true;
                        $_SESSION["id"] = $row["empId"];
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["firstName"] = $row["firstName"];
                        $_SESSION["lastName"] = $row["lastName"];
                        $accountTableempId =  $row["empId"];
                        // DETERMINE TYPE OF USER VIA QUERING FROM EMPLOYEE TABLES (Consultant, Manager, FinanceMember)
                        // AND CHECKING AGAINST THE ACCOUNT EMP ID
                        // Consultant check
                        $this->consultantCheck($accountTableempId);
                        // Manager check
                        $this->managerCheck($accountTableempId);
                        // Admin check
                        $this->adminCheck($accountTableempId);
                        // Finance member check
                        $this->financeCheck($accountTableempId);
                        // Returns true
                        return true;
                    }
                }
            }
        }
        // Returns false
        return false;
    }

    private function consultantCheck($accountTableempId) {
        $sql = "SELECT * FROM Consultant c WHERE '$accountTableempId'= c.empId";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Employee is a consultant.
            $_SESSION["type"] = "consultant";
        }
    }

    private function managerCheck($accountTableempId) {
        $sql = "SELECT * FROM Manager m WHERE '$accountTableempId'= m.empId";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Employee is a consultant line mananger.
            $_SESSION["type"] = "manager";
        }
    }

    private function adminCheck($accountTableempId) {
        $sql = "SELECT * FROM Administrator a WHERE '$accountTableempId'= a.empId";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Employee is an administrator.
            $_SESSION["type"] = "admin";
        }
    }

    private function financeCheck($accountTableempId) {
        $sql = "SELECT * FROM FinanceMember f WHERE '$accountTableempId'= f.empId";
        $result = $this->connect()->query($sql);
        if ($result->num_rows == 1) { // Employee is a financial member.
            $_SESSION["type"] == "finance";
        }
    }
}
?>