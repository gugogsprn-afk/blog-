<?php
    
    class UserValidator  {
       
        
      
        public function __construct() {
            if (_REQINT('logout')==1) $this->Logout();
        }
        
        private function Attempted() {
            $_SESSION['m_login_attempt'] = intval($_SESSION['m_login_attempt']) + 1;
        }
        
        public static function isAttempted() {
            return intval($_SESSION['m_login_attempt'])>0;
        }

        public function ValidateUser($username, $password, $captcha, $userType) {
            $bl = false;
            $this->Attempted();
            if (!empty($username) && !empty($password)) {
               
               if (intval($_SESSION['m_login_attempt'])>1) {
                    $captcha_code = $_SESSION["g_captch"];
                    unset($_SESSION["g_captch"]);
                    $_SESSION["g_captch"]='';
                    if(empty($captcha) || $captcha!==$captcha_code) {
                        throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGCAPTCHA');
                    }
               }
               $identityService = new UserIdentityService();
               $userInfo = $identityService->getUser($username);
               if (!empty($userInfo) && !empty($userInfo['usr_pass']) && $userInfo['mtype']===$userType) {
                   if ($userInfo['usr_pass'] === md5(md5($password).$userInfo['salt'])) {
                       $hash = $identityService->updateHashAndLoginCnt(_VARINT($userInfo['id']));
                       if ($hash!==false) {
                           session_regenerate_id(TRUE); 
                           $_SESSION['m_usr_id']=$userInfo['id'];
                           $_SESSION['m_usr_hash']=$hash;
                           $_SESSION['m_login_attempt']=0;
                           $bl = true;
                       } else {
                           throw new ESecurityError(ESecurityError::ACCESS_DENIED);
                       }
                   } else {
                       throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGLOGINPASS');
                   }
               } else {
                   throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGLOGINPASS');
               }
            } else {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGLOGINPASS');
            }
            return $bl;
        }
        
        public function ValidateMobileUser($username, $passwordMd5, $userType) {
            $bl = false;
            $this->Attempted();
            if (!empty($username) && !empty($passwordMd5)) {
               if (intval($_SESSION['m_login_attempt'])>1) {
                    // no captcha on mobile -> block after 3 atempts?
                    /*
                    $captcha_code = $_SESSION["g_captch"];
                    unset($_SESSION["g_captch"]);
                    $_SESSION["g_captch"]='';
                    if(empty($captcha) || $captcha!==$captcha_code) {
                        throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGCAPTCHA');
                    }
                    */
               }
               $identityService = new UserIdentityService();
               $userInfo = $identityService->getUser($username);
               if (!empty($userInfo) && !empty($userInfo['usr_pass']) && $userInfo['mtype']===$userType) {
                   if ($userInfo['usr_pass'] === md5($passwordMd5.$userInfo['salt'])) {
                       $hash = $identityService->updateHashAndLoginCnt(_VARINT($userInfo['id']));
                       if ($hash!==false) {
                           session_regenerate_id(TRUE); 
                           $_SESSION['m_usr_id']=$userInfo['id'];
                           $_SESSION['m_usr_hash']=$hash;
                           $_SESSION['m_login_attempt']=0;
                           $bl = true;
                       } else {
                           throw new ESecurityError(ESecurityError::ACCESS_DENIED);
                       }
                   } else {
                       throw new EInvalidInputError(array('email'),'ERR_WRONGLOGINPASS');
                   }
               } else {
                   throw new EInvalidInputError(array('email'),'ERR_WRONGLOGINPASS');
               }
            } else {
                throw new EInvalidInputError(array('email'),'ERR_WRONGLOGINPASS');
            }
            return $bl;
        }
        
        public function storeUser($userID) {
            $identityService = new UserIdentityService();
            $hash = $identityService->updateHash(_VARINT($userID));
            if ($hash!==false) {
                session_regenerate_id(TRUE); 
                $_SESSION['m_usr_id']=_VARINT($userID);
                $_SESSION['m_usr_hash']=$hash;
                $_SESSION['m_login_attempt']=0;
                return true;
            }
            return false;
        }
        
        /**
         * 
         * @return Identity
         */
        public function Create() {
            $CreatedUser = null;
            try {
                if (isset($_SERVER['HTTP_X_AUTHORIZATION']) && !empty($_SERVER['HTTP_X_AUTHORIZATION'])) {
                    $dec = base64_decode($_SERVER['HTTP_X_AUTHORIZATION']);
                    list($userID,$userHash) = explode('-', $dec);
                    $userID = intval($userID);
                    if ($userID>0 && !empty($userHash)) {
                        $_SESSION['m_usr_id'] = $userID;
                        $_SESSION['m_usr_hash'] = $userHash;
                    }
                } 
                if (isset($_SESSION['m_usr_id']) && isset($_SESSION['m_usr_hash'])) {
                    if (intval($_SESSION['m_usr_id']) > 0) {
                        $identityService = new UserIdentityService();
                        $userInfo = $identityService->getUserInfo(intval($_SESSION['m_usr_id']));
                        if (array_isset($userInfo) && intval($userInfo['id'])>0) {
                            if ($userInfo['usr_hash'] === $_SESSION['m_usr_hash']) {
                                //$permissions = $identityService->getPermissions($userInfo['role_id']);
                                $CreatedUser = new Identity($userInfo, explode(',', $userInfo['roles']));
                                /*
                                if (array_isset($permissions)) {
                                    
                                }
                                */
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
            unset($_SESSION['m_usr_id']);
            unset($_SESSION['m_usr_hash']);
        }

        
        
        
    }


?>