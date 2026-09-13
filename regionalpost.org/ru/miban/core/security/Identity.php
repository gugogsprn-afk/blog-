<?php

    class Identity {
    
        public $username='';
        public $ID=null;
        private $permissions = array();
        public $name = '';
        
        public static $permsToInt = array(
            'r' => 1,
            'w' => 2,
            'e' => 4,
            'd' => 8
        );

        public function __construct($userInfo,$permrow = array()) {
            $this->ID = _VARINT($userInfo['id']);
            if (empty($this->ID)) throw new ESecurityError(ESecurityError::ACCESS_DENIED);
            $this->username = $userInfo['usr_name'];
            $this->name = $userInfo['name'];
            foreach ($permrow as $key => $value) {
                if ($key=='id' || $key=='name' || $key=='descr')  continue;
                $permType = substr($key, 0,1);
                if ($permType==='p') {
                    $this->permissions[$key]=intval($value);
                } else if ($permType==='c') {
                    $value = trim($value);
                    $this->permissions['category'] = array();
                    if (!empty($value)) {
                        $this->permissions['category'] = DeserilizeJson($value);
                    }
                }
            }
        }
        
        /*
        1248
        rwed
         * 
         */
        public function HasPermission($controller,$rwed) {
            // $this->permissions['category']
            // $this->permissions['p_catalog'] // default category permissions
            // $this->permissions['p_item'] // default item permissions
            // $this->permissions['p_file'] // default file permissions
            if (empty($rwed)) return true;
            $controller = trim(strtolower($controller));
            $isHashPermission = false;
            if (array_key_exists('p_'.$controller, $this->permissions)) {
                $reqPerm = intval(Identity::$permsToInt[$rwed]);
                $MemberPerm = intval($this->permissions['p_'.$controller]);
                if ($reqPerm>0 && $MemberPerm>0) {
                    $isHashPermission = (($MemberPerm & $reqPerm)===$reqPerm);
                }
            }
            return $isHashPermission;
        }
        
        public function HasPermissionOn($RootID,$rwed) {
            if (empty($rwed)) return true;
            $isHashPermission = false;
            if (array_key_exists($RootID, $this->permissions['category'])) {
                $reqPerm = intval(Identity::$permsToInt[$rwed]);
                $MemberPerm = intval($this->permissions['category'][$RootID]);
                if ($reqPerm>0 && $MemberPerm>0) {
                    $isHashPermission = (($MemberPerm & $reqPerm)===$reqPerm);
                }
            } else {
                return null;
            }
            return $isHashPermission;
        }
        
        public function getCategoriesWithReadPermissions() {
            if ($this->HasPermission('category', 'r')) {
                return false;
            }
            $catIDs = array();
            foreach ($this->permissions['category'] as $key => $value) {
                $reqPerm = intval(Identity::$permsToInt['r']);
                $MemberPerm = intval($value);
                if ($reqPerm>0 && $MemberPerm>0) {
                    if (($MemberPerm & $reqPerm)===$reqPerm) {
                        $catIDs[] = _VARINT($key);
                    }
                }
            }
             
            return $catIDs;
        }

        public function getInfo() {
            return array();
        }
        
        public function setProperties() {
            
        }


   }
?>
