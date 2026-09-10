<?php

    class UserValidator  {
        
         public function __construct() {
            if (intval(_REQUEST('logout'))==1) $this->Logout();
        }
        
        private function Attempted() {
            $_SESSION['login_attempt'] = intval($_SESSION['login_attempt']) + 1;
        }
        
        public function ValidateUser($username, $password, $captcha) {
            $bl = false;
            $this->Attempted();
            
            if (!empty($username) && !empty($password)) {
               if (intval($_SESSION['login_attempt'])>1) {
                    $captcha_code = $_SESSION["sec_codecacha"];
                    unset($_SESSION["sec_codecacha"]);
                    $_SESSION["sec_codecacha"]='';
                    if(empty($captcha) || $captcha!==$captcha_code) {
                        throw new ESecurityError(ESecurityError::WRONG_CAPTCHA);
                    }
               }
               $identityService = new UserIdentityService();
               $userInfo = $identityService->getUser($username);
               if (!empty($userInfo)) {
                   if ($userInfo['usr_pass'] === md5(md5($password).$userInfo['salt'])) {
                       if ($userInfo['daysleft'] !== null && intval($userInfo['daysleft']<0)) {
                           throw new ESecurityError(ESecurityError::MEMBER_EXPIRED);
                       }
                       
                       
                       $hash = $identityService->updateHash(_VARINT($userInfo['id']));
                       if ($hash!==false) {
                           session_regenerate_id(TRUE); 
                           $_SESSION['usr_adminid']=$userInfo['id'];
                           $_SESSION['usr_adminhash']=$hash;
                           $_SESSION['login_attempt']=0;
                           $bl = true;
                       } else {
                           throw new ESecurityError(ESecurityError::ACCESS_DENIED);
                       }
                   } else {
                       throw new ESecurityError(ESecurityError::WRONG_PASS);
                   }
               } else {
                   throw new ESecurityError(ESecurityError::USER_NOT_FOUND);
               }
            } else {
                throw new ESecurityError(ESecurityError::BLANK_PASS);
            }
            return $bl;
        }
        
        
        /**
         * 
         * @return Identity
         */
        public function Create() {
            $CreatedUser = null;
            try {
                if (isset($_SESSION['usr_adminid']) && isset($_SESSION['usr_adminhash'])) {
                    if (intval($_SESSION['usr_adminid']) > 0) {
                        $identityService = new UserIdentityService();
                        $userInfo = $identityService->getUserInfo(intval($_SESSION['usr_adminid']));
                        if ($userInfo != false) {
                            if ($userInfo['usr_hash'] === $_SESSION['usr_adminhash']) {
                                $permissions = $identityService->getPermissions($userInfo['role_id']);
                                if (array_isset($permissions)) {
                                    $CreatedUser = new Identity($userInfo, $permissions);
                                }
                            }
                        }
                    }
                }
            } catch (Exception $ex) {
                // TODO: what?
                $CreatedUser = null;
                throw $ex;
            }
            return $CreatedUser;
        }

        public function Logout() {
            unset($_SESSION['usr_adminid']);
            unset($_SESSION['usr_adminhash']);
        }

        
        
        public function setSessionFromHeader() {
           
        }
        
        
        public function updateHash($userID) {
            $identityService = new UserIdentityService();
            $hash = $identityService->updateHash(_VARINT($userID));
            if ($hash!==false) {
                session_regenerate_id(TRUE); 
                $_SESSION['m_usr_id']=_VARINT($userID);
                $_SESSION['m_usr_hash']=$hash;
                $_SESSION['m_login_attempt']=0;
                return $hash;
            }
            return false;
        }
        
        
        
        
    }


?>