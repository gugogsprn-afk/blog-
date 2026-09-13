<?php

    abstract class IdentityServiceBase {
        
        
        
        public function __construct() {
           
        }
        
        abstract public function getUser($userName);
        abstract public function getUserInfo($userID);
        abstract public function updateHash($userID);
        abstract public function getPermissions($roleID);

    }

    
?>