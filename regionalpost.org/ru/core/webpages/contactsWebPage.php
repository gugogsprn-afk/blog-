<?php

    class contactsWebPage extends MasterWebPage {

        protected $Styles = array();
        
        protected function getSlider() {
            return null;
        }
        public function Index() {
            $item = new Page(HttpContext::current()->request()->itemID);
            $item->Load();
            
            if (HttpContext::current()->request()->IsAjax() && _POSTINT('cmd')===1) {
                $this->SendMessage();
                return;
            }
            
            $currentTitle = Uri::FindUrl('/')->getProp('meta_title');
            if ($this->metas['metas']['title'] == '') {
                $this->metas['metas']['title'] = $currentTitle;
            }
            
            $page = $item->toArray();
            $tpl = new Template('contacts.inc');
            
            $opt = Configuration::BulkLoad(array('site_mailform', 'site_geo', 'mail_support', 'site_company', 'phone', 'address'));
            if (!empty($page['tags'])) {
                $geo = array();
                list($geo['lat'], $geo['long'], $geo['zoom']) = explode(';', $page['tags']);
                $tpl->vars['geo'] = $geo;
            }
            $tpl->vars['sendmail'] = 'true';
            if ($opt['site_mailform'] == 'false')
                $tpl->vars['sendmail'] = '';
            $tpl->vars['item'] = $page;
            $tpl->vars['mailsupport'] = $opt['mail_support'];
            $tpl->vars['phone'] = $opt['phone'];
            $tpl->vars['address'] = $opt['address'];
            $tpl->vars['site_company'] = $opt['site_company'];
            $tpl->vars['mapkey'] = isLocalHost()?'':Configuration::Load('mapkey');
            $this->contents =  $tpl->Render();
        }
        
       
        private function SendMessage() {
            $mailConf = Configuration::BulkLoad(array('mail_server','mail_support','mail_method'));
            if ($mailConf['site_mailform']=='false') {
                throw new EPageError(EPageError::PAGE_NOT_FOUND);
            }
            
            $dict = Dictionary::BulkLoad(array('MAIL_SENT'));
            
            $userName =  htmlspecialchars(_VAR($_POST['member']['name']));
            $userMail = _VAR($_POST['member']['mail']);
            $userPhone = _VAR($_POST['member']['phone']);
            $messageSubject = $userName;
            $messageContent = _VAR($_POST['message']['content']);
            $captcha = _POST('seccode');
            if (!$this->IsValidCaptha($captcha))
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_WRONGCAPTCHA');
            if (empty($userName))
                throw new EInvalidInputError(array('name'));
            if (empty($messageContent))
                throw new EInvalidInputError(array('message'));
            
            if (empty($userMail))
                throw new EInvalidInputError(array('mail'));
            if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',$userMail)) 
                throw new EInvalidInputError(array('mail'));
            
            $ok = false;
            $error = '';
            $messageContent =htmlspecialchars(mb_str_replace('\\','',$messageContent.' .phone : '.$userPhone));
            
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

                $mail->IsHTML(false);   
                $mail->AddReplyTo($userMail, $userName);
                $mail->SetFrom($userMail, $userName);
                $mail->AddAddress($mailConf['mail_support']); 
                $mail->Subject = 'Feedback from site';
                $mail->Body = $messageContent;
                $mail->CharSet="UTF-8";
                $ok = $mail->Send();
                $error =$mail->ErrorInfo;
            } catch (phpmailerException $ex) {
               // throw new EPageError(EPageError::CUSTOM_ERROR,$ex->errorMessage());
            } catch (Exception $ex) {
              //  throw new EPageError(EPageError::CUSTOM_ERROR, $ex->getMessage());
            }

            if ($ok===false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_SENDMAIL'); // TODO:$error remove debug info
            }
            $this->contents = '{ "result":"ok","info":"'.$dict['MAIL_SENT'].'" }';
        }
        
        private function IsValidCaptha($captcha) {
            $captcha_code = $_SESSION['m_captch'];
            $captcha_code = trim($captcha_code);
            if (empty($captcha_code)) return FALSE;
            unset($_SESSION['m_captch']);
            $_SESSION['m_captch']='';
            if(empty($captcha) || $captcha!==$captcha_code) {
                return false;
            }
            return true;
        }
        
        
        

    }

?>
