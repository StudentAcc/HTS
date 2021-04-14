<?php
class ChangeDetails extends Connect { 
    public function changePassword($username, $password, $new_password) {
        $sql = "UPDATE Account SET password = ('$new_password') WHERE username = '$username' AND password = '$password'";
        $this->connect()->query($sql);
    }
}
?>