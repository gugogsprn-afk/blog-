<?php

    class AdminMember extends DataAdapter {

        // must override
        protected $table = ' #__control';
        protected $idField = 'id';
        protected $FieldArray = Array('id', 'usr_name', 'usr_pass', 'salt', 'usr_hash', 'usr_active', 'created_~', 'last_visit_~', 'current_visit_~', 'role_id', 'name','validdate_$',  'thumbprint', 'thumbtype','descr','TIMESTAMPDIFF(DAY,created,CURDATE()) as daysinsite','TIMESTAMPDIFF(DAY,CURDATE(),validdate) as daysleft','enabled');
        protected $CollectionFieldArray = Array('tu.id', 'tu.usr_name','created_~', "tu.last_visit_~","tu.current_visit_~", 'tu.role_id', 'tu.name', 'tu.usr_active','tr.name as role_name','TIMESTAMPDIFF(DAY,CURDATE(),tu.validdate) as daysleft','enabled');

        private $mailRegex = "/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/";
        
        const THUMB_REGISTER = 'REG';
        const THUMB_RESETPASS = 'RSTPASS';
        
       
        
       
        
        public function LoadFrom($mixedHash,$thumbType) {
            if (!$this->BeforeLoad()) return false;
            $enc = new Encryptor();
            $parts = $enc->Decrypt($mixedHash);
            if ($parts===false) 
                return false;
            $this->ID = intval($parts['data']);
            $DB = DatabaseProvider::provide();
            $thumbType = $DB->EscapeValue($thumbType);
            $row = $DB->Fetch($this->getSelectHashQuery($thumbType));
            if ($row===false || intval($row['id'])<=0 || $row['usr_hash']!==$parts['hash'] || $row['thumbprint']!==$enc->MD5($mixedHash)) {
                $this->ID = 0;
                return false;
            }
            $this->bind($row);
            $this->ID = intval($row['id']);
            $this->OnLoad(true);
            return $row;
        }
        
        protected function bind($data) {
            $this->Props = $data;
        }
        
        private function getSelectHashQuery($thumbType) {
            $qry = new QueryBuilder();
            $sID = intval($this->ID);
            return $qry->Select($this->FieldArray,$this->table)->Where($this->idField.'='.$sID.' AND thumbtype='.$thumbType)->GetQuery();
        }
        
        public function LoadCollection($pageSize,$startIndex,$pageNum,$orderby) {
            if ($pageNum<=0)                
                throw new EPageError(EPageError::CUSTOM_ERROR,"page number is wrong");
            $ItemCollection = $this->Collection(true);
            $where = '1=1';
            if (isset($this->Props) && is_array($this->Props) && count($this->Props)>0) {
                $qry = new QueryBuilder();
                $where = $qry->BuildConditions($this->Props);
            }
            $queryBuilder = new QueryBuilder();
            $queryBuilder->Select($this->CollectionFieldArray, $this->table.' tu')
                            ->Join('#__roles tr', 'tu.role_id = tr.id','INNER')
                            ->Where($where);
            if (!empty($orderby)) {
                $queryBuilder->OrderBy($orderby);
            }
            $queryBuilder->Limit($startIndex, $pageSize);        
            $ItemCollection->SetQuery($queryBuilder->GetQuery());
            $ItemCollection->Load();
            $DB = DatabaseProvider::provide();
            $totalRows = $DB->Scalar("SELECT count(*) FROM ".$this->table);
            $arr = array( "page" => $pageNum,"records" => $totalRows, "rows" => $ItemCollection->toArray());
            return $arr;
        }
        
        
        public function Add($itemProps) {
            $DB = DatabaseProvider::provide();
            
            $usrName = $itemProps['usr_name'];
            $descr = $DB->EscapeValue($itemProps['descr'],false);
            $roleID = _VARINT($itemProps['role_id']);
            $isTemp = _VARINT($itemProps['temporary'])>0;
            $validDate = _VAR($itemProps['validdate']);
            
            $invalidFields = array();
            if (!preg_match($this->mailRegex,$usrName)) {
                $invalidFields[] = 'member-usr_name';
            }
            if ($isTemp) {
                $dateObject = ParseDate($validDate);
                $now = getdate();
                if ($dateObject==null || $dateObject<$now[0]) {
                    $invalidFields[] = 'member-validdate';
                }
                $validDate = "STR_TO_DATE(".$DB->EscapeValue($validDate).",'%d.%m.%Y')";
            } else {
                $validDate = 'NULL';
            }
            
            if (count($invalidFields)>0)               
               throw new EInvalidInputError($invalidFields);
            
            
            $memberInDB = $this->GetUser($usrName);
            if ($memberInDB['exists']===true) {
                $isActivated = _VARINT($memberInDB['usr_active']);
				$msg = ($isActivated?'User already exists and activated':'User already exists and not activated yet');
                throw new EPageError(EPageError::CUSTOM_ERROR,$msg);
            }
            
            
            $salt = generateString(4);
            $enc = new Encryptor(); 
            $usrHash = $enc->newMD5();
            
            try {
                $ok = $DB->Query("INSERT INTO ".$this->table." SET
                        usr_name = '".$usrName."',
                        usr_hash = '".$usrHash."',
                        salt = '".$salt."',
                        usr_active = 0,
                        last_visit = NOW(),
                        current_visit = NOW(),
                        role_id = ".$roleID.",
                        validdate = ".$validDate.",
                        descr = '".$descr."'");

                if (!$ok) {
                    $this->OnInsert(false);
                    throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating new user');
                }

                $this->ID =  _VARINT($DB->LastID());
                if (empty($this->ID)) {
                    $this->OnInsert(false);
                    throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating new user');    
                }

                $hash = $enc->Encrypt($usrHash, $this->ID);
                $thumbprint = $enc->MD5($hash);
                $thumbType = self::THUMB_REGISTER;

                $ok = $DB->Query("UPDATE ".$this->table." SET
                        thumbprint = '".$thumbprint."',
                        thumbtype = '".$thumbType."'
                        WHERE id=".$this->ID);

                if (!$ok) {
                    $this->OnInsert(false);
                    throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating new user');
                }
                
                $this->setProp('usr_name', $usrName);
                $this->setProp('usr_hash', $usrHash);
                $this->setProp('salt', $salt);
                $this->setProp('usr_active', 0);
                $this->setProp('role_id', $roleID);
                $this->setProp('validdate', $validDate);
                $this->setProp('descr', $descr);
                $this->setProp('thumbprint', $thumbprint);
                $this->setProp('thumbtype', $thumbType);
                $this->setProp('activationhash', $hash);
                
                $this->OnInsert(true);
            } catch (Exception $ex) {
                if (!empty($this->ID)) {
                    $this->Remove();
                }
                throw $ex;
            }
        }
        
        
        protected function OnInsert($success) {
            if ($success && !empty($this->ID)) {
                $hash = $this->getProp('activationhash');
                $mailConf = Configuration::BulkLoad(array('mail_server','mail_support','mail_template_activ','mail_support_name','mail_active_subject','mail_method'));
                $ActivationUrl = HttpContext::current()->request()->baseAddress.'index.php?show=activate&h='.$hash;
                $message = mb_str_replace('[link]',  $ActivationUrl , $mailConf['mail_template_activ']); 
                $ok = $this->sendMail($this->getProp('usr_name'), $mailConf['mail_active_subject'], $message, $mailConf);
                if ($ok===false) {
                    $this->Remove();
                    throw new EPageError(EPageError::CUSTOM_ERROR,'Error sending email message. '.$this->mailError);
                }
            }
        }
        
        

        public function Edit($itemProps) {
            $captcha = $itemProps['captcha'];
            $pass1 = $itemProps['passa'];
            $pass2=  $itemProps['passb'];
            $name = $itemProps['name'];
            
            $captcha_code = $_SESSION["sec_codecacha"];
            unset($_SESSION["sec_codecacha"]);
            $_SESSION["sec_codecacha"]='';
            if(empty($captcha) || $captcha!==$captcha_code) {
                throw new ESecurityError(ESecurityError::WRONG_CAPTCHA);
            }
            
            if (empty($pass1) || empty($pass2)) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Enter password');
            if ($pass1 !== $pass2) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Passwords not match');

            if (empty($name))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Enter your name');
            
            $enc = new Encryptor();
            $salt = $this->getProp('salt');
            $password = $enc->SaltMD5($pass1, $salt);
            
            $DB = DatabaseProvider::provide();
       
            $ok = $DB->Query("UPDATE #__control SET
                    usr_pass = '".$password."',
                    usr_active = 1,
                    name = ".$DB->EscapeValue($name).",
                    thumbprint = '',
                    thumbtype = ''
                    WHERE ID=".intval($this->ID));
            $this->OnUpdate($ok);
            $_SESSION['login_attempt'] = 0;
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error activating account, please try again later.');
            }
            return true;
        }

        
        public function EditProperties($itemProps) {
            $DB = DatabaseProvider::provide();
            
            $isTemp = _VARINT($itemProps['temporary'])>0;
            $validDate = _VAR($itemProps['validdate']);
            $invalidFields = array();
            if ($isTemp) {
                $dateObject = ParseDate($validDate);
                $now = getdate();
                if ($dateObject==null || $dateObject<$now[0]) {
                    $invalidFields[] = 'member-validdate';
                }
                $validDate = "STR_TO_DATE(".$DB->EscapeValue($validDate).",'%d.%m.%Y')";
            } else {
                $validDate = 'NULL';
            }
            if (count($invalidFields)>0)               
               throw new EInvalidInputError($invalidFields);
            
            
            $DB->Query("UPDATE ".$this->table." SET
                        name = ".$DB->EscapeValue($itemProps['name']).",
                        role_id = "._VARINT($itemProps['role_id']).",
                        validdate = ".$validDate.",
                        descr = ".$DB->EscapeValue($itemProps['descr'])."
                        WHERE id=".  _VARINT($this->ID));
            return true;
        }
        
        public function ChangePass($current,$pass1,$pass2) {
            if ($this->getProp('usr_pass') !== md5(md5($current).$this->getProp('salt'))) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'Wrong password');
            }
            if (empty($pass1) || empty($pass2)) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Enter password');
            if ($pass1 !== $pass2) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Passwords not match');
            $enc = new Encryptor();
            $salt = generateString(4);
            $password = $enc->SaltMD5($pass1, $salt);
            $DB = DatabaseProvider::provide();
            $ok = $DB->Query("UPDATE #__control SET
                    salt = '".$salt."',
                    usr_pass = '".$password."'
                    WHERE ID=".intval($this->ID));
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error changing password, please try again later.');
            }
            return true;
        }
        

        public function Remove() {
            if ($this->ID==1)
                throw new EPageError(EPageError::CUSTOM_ERROR,'Unable to delete root user');
            parent::Delete();
        }
        
        public function ToggleEnabled($isEnabled) {
            $enabled = $isEnabled===true?1:0;
            $DB = DatabaseProvider::provide();
            $DB->Query('UPDATE '.$this->table.' SET enabled='.$enabled.' WHERE id='._VARINT($this->ID));
            return true;
        }
        
        
        private $mailError = '';
        
        private function sendMail($email,$subject,$message,$mailConf) {
            $ok = false;
            $this->mailError = '';
           
            try {
                $mail = new PHPMailer;
                switch ($mailConf['mail_method']) {
                    case 'sendmail':
                        $mail->IsSendmail();
                        break;
                    case 'mail':
                        $mail->IsMail();
                        break;
                    default:
                        $mail->IsSMTP();
                        break;
                }
                if (!empty($mailConf['mail_server'])) {
                    $mail->Host = $mailConf['mail_server']; 
                }

                $mail->IsHTML(true);   
                $mail->From = $mailConf['mail_support'];
                $mail->FromName = $mailConf['mail_support_name'];
                $mail->AddAddress($email);  
                $mail->AddReplyTo($mailConf['mail_support'], $mailConf['mail_support_name']);
                $mail->Subject = $subject;
                $mail->MsgHTML($message);
                $mail->CharSet="UTF-8";
                $ok =  $mail->Send();
                $this->mailError =$mail->ErrorInfo;
            } catch (phpmailerException $ex) {
                $this->mailError = $ex->getMessage();
            } catch (Exception $ex) {
                $this->mailError = $ex->getMessage();
            }
            return $ok;
        }
        
        
        public function GetUser($usrName) {
            $DB = DatabaseProvider::provide();
            $usrName =$DB->EscapeValue($usrName);
            $row = $DB->Fetch("SELECT id,usr_active FROM ".$this->table." WHERE thumbtype<>'REF' AND usr_name=".$usrName);
            if ($row === false)
                $row = array();
            $row['exists'] = (intval($row['id'])>0?true:false);
            return $row;
        }
        
        public function isActivationRequired($hash) {
            $userInfo = $this->LoadFrom($hash,self::THUMB_REGISTER);
            if ($userInfo != false) {
                if (intval($userInfo['usr_active'])===0) {
                    $days = intval($userInfo['daysinsite']);
                    $dy = Configuration::Load('member_activatedays');
                    if ($days>intval($dy)) {
                        $this->Remove();
                        throw new ESecurityError(ESecurityError::ACTIVATION_EXPIRED);
                    } else {
                        return $userInfo;
                    }
                } else {
                    throw new ESecurityError(ESecurityError::ALREADY_ACTIVATED);
                }
                
            }
            return false;
        }
        
        
        
    }

?>
