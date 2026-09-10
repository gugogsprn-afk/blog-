<?php
    abstract class UserValidatorBase {
    
        public abstract function ValidateUser($username, $password, $captcha);
        public abstract function Create();
        public abstract function Logout();
        

        }
    
?>
