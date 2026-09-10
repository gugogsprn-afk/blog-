<?php

    class UserIdentityService extends IdentityServiceBase {

        public function getUser($userName) {
            //id, usr_name, usr_pass, salt, usr_hash, usr_active, created, last_visit, current_visit, NAME, thumbprint, thumbtype, mtype, roles, info
            $DB = DatabaseProvider::provide();
            $userInfo = $DB->Fetch("SELECT id, usr_name, usr_pass, salt, mtype,name,phone,marker,enabled FROM #__members WHERE usr_name=" . $DB->EscapeValue($userName) . " AND usr_active=1 AND enabled=1 LIMIT 1");
            if ($userInfo == false)
                return null;
            if (intval($userInfo['id'])<=0)
                return null;
            return $userInfo;
        }

        public function updateHash($userID) {
            $sess_hash = md5(generateString(10));
            $DB = DatabaseProvider::provide();
            $ok = $DB->Query("UPDATE #__members SET last_visit=current_visit, current_visit=NOW(), usr_hash='" . $sess_hash . "' WHERE id=".intval($userID));
            if ($ok == false)
                return false;
            return $sess_hash;
        }
        
        public function updateHashAndLoginCnt($userID) {
            $sess_hash = md5(generateString(10));
            $DB = DatabaseProvider::provide();
            $ok = $DB->Query("UPDATE #__members SET last_visit=current_visit, current_visit=NOW(),isfirstlogin=0, usr_hash='" . $sess_hash . "' WHERE id=".intval($userID));
            if ($ok == false)
                return false;
            return $sess_hash;
        }
       
        
        public function getUserInfo($userID) {
            $DB = DatabaseProvider::provide();
            $userInfo = $DB->Fetch('SELECT id, usr_name, usr_hash, name,mtype,roles,name,phone,member_ref,
                            s_member_id,marker,TIMESTAMPDIFF(DAY,created,NOW()) AS daysinsite,usernum,avgrate,avgcr,avgratecnt,avgratea,avgrateb,avgratec,
                            m_hvhh,m_country,m_countryraw,m_address,m_langs,m_hours,m_propsa,enabled
                            FROM #__members WHERE usr_active=1  AND enabled=1 AND id='.intval($userID));
            return $userInfo;
        }

        

        public function getPermissions($roleID) {
            return null;
        }
    }
?>