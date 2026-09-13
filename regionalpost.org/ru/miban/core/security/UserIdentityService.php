<?php

    class UserIdentityService extends IdentityServiceBase {

        public function getUser($userName) {
            $DB = DatabaseProvider::provide();
            $userName = $DB->EscapeValue($userName);
            $userInfo = $DB->Fetch('SELECT id, usr_name, usr_pass, salt, name,(CASE validdate WHEN null THEN null ELSE TIMESTAMPDIFF(DAY,CURDATE(),validdate) END) as daysleft FROM #__control WHERE usr_active=1 AND enabled=1 AND usr_name=' . $userName . ' LIMIT 1');
            if ($userInfo == false)
                return null;
            return $userInfo;
            
            
        }

        public function getUserInfo($userID) {
            $DB = DatabaseProvider::provide();
            $userInfo = $DB->Fetch('SELECT id, usr_name, usr_hash, role_id, name,TIMESTAMPDIFF(DAY,CURDATE(),validdate) as daysleft FROM #__control WHERE usr_active=1 AND enabled=1 AND id='.$userID.' LIMIT 1');
            return $userInfo;
        }

        public function updateHash($userID) {
            $sess_hash = md5(generateString(10));
            $DB = DatabaseProvider::provide();
            $ok = $DB->Query("UPDATE #__control SET last_visit=current_visit, current_visit=NOW(), usr_hash='" . $sess_hash . "' WHERE id=".$userID);
            if ($ok == false)
                return false;
            return $sess_hash;
        }
        
        
        public function getPermissions($roleID) {
            $roleID = intval($roleID);
            if ($roleID<=0) return array();
            $DB = DatabaseProvider::provide();
            $permissions = $DB->Fetch('SELECT * FROM #__roles WHERE id='.$roleID);
            if ($permissions===false)
                return array();
            return $permissions;
        }
        

    }

?>
