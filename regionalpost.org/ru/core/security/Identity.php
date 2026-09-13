<?php

    class Identity {
    
        public $username='';
        public $ID=null;
        public $Roles = array();     
        
        private $info = array();
        public $name = '';
        
        
        public $enabled = false;


        public function __construct($userInfo,$roleList = array()) {
            $this->ID = _VARINT($userInfo['id']);
            if (empty($this->ID)) throw new ESecurityError(ESecurityError::ACCESS_DENIED);
            $this->username = $userInfo['usr_name'];
            $this->name = $userInfo['name'];
            $this->Roles = $roleList;
            $this->info = $userInfo;
            $this->enabled = intval($userInfo['enabled'])>0;
        }
        
        public function IsInRole($role) {
            return (is_array($this->Roles) && in_array($role, $this->Roles));
        }
        
        public function getInfo() {
            return $this->info;
        }
        
        public function setInfo($value) {
            $this->info = $value;
        }

        public function getInfoValue($prop) {
            return $this->info[$prop];
        }
        
        public function setProperties() {
            
        }
        

   }
?>
