<?php
class ChangeDetails extends Connect { 
    public function changePassword($username, $password, $new_password) {
        $sql = "UPDATE account a SET a.password = ('$new_password') WHERE username = '$username' AND a.password = '$password'";
        $this->connect()->query($sql);
    }
}
?>