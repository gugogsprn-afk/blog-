<?php
class LoginWebPage extends WebPageBase {
    
    protected $Styles = array();
    
    /**
     *
     * @var Template
     */
    protected $MainTpl = null;
    protected $contents = '';


    
    public function __construct() {
        
    }
    
    public function Initilize() {
        $this->MainTpl = new Template('login_layout.inc');
    }

    public function Index() {
        if (_REQUEST('show')==='activate') {
            $this->Activate();
            return;
        }
            
        $tpl = new Template('login.inc');
        try {
            if (_POSTINT('login') === 1) {
                $usrLogin = _POST('usr_login', '');
                $usrPass = _POST('usr_password', '');
                $captcha =_POST('fb_code', '');

                $validator = new UserValidator();
                $validated = $validator->ValidateUser($usrLogin, $usrPass, $captcha);
                if ($validated) {
                      $identity = $validator->Create();
                      HttpContext::current()->SetIdentity($identity);
                }
            }
        } catch (ESecurityError $ex) {
            $msg = $ex->getMessage();
            $this->MainTpl->vars['message'] = $this->translateError($msg);
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            $this->MainTpl->vars['message'] = $this->translateError($msg);
        }
        if (_VARINT($_SESSION['login_attempt'])>0) {
            $tpl->vars['captcha'] = 'true';
        }
        
        
        $tpl->vars['currenturl'] = HttpContext::current()->request()->reletiveUrl;
        $this->assignAdminLangSwitcher($tpl);
        $this->contents = $tpl->Render();
    }
    
    
    public function Display() {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:');
        //header_remove("Last-Modified"); 
        
        $footer = new Template('footer.inc');
        $year = date('Y');
        $yearInterval = '2010 - '.$year;
        $footer->vars['years'] = $yearInterval;
        $footer->vars['year'] = $year;
        
        $this->MainTpl->vars['footer'] = $footer->Render();
        $this->MainTpl->vars['baseaddress'] = HttpContext::current()->request()->baseAddress;
        $this->MainTpl->vars['apptitle'] = Configuration::Load('sitename');
        $this->MainTpl->vars['contents'] = $this->contents;
        $this->assignAdminLangSwitcher($this->MainTpl);
        echo $this->MainTpl->Render();
    }

    protected function assignAdminLangSwitcher($tpl) {
        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
        $lang = 'en';
        if (strpos($script, '/ru/miban') !== false) {
            $lang = 'ru';
        } elseif (strpos($script, '/fr/miban') !== false) {
            $lang = 'fr';
        }
        $labels = array('en' => 'English', 'ru' => 'Russian', 'fr' => 'French');
        $origin = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
            . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'regionalpost.org');
        $tpl->vars['admin_lang'] = $lang;
        $tpl->vars['admin_lang_label'] = isset($labels[$lang]) ? $labels[$lang] : strtoupper($lang);
        $tpl->vars['admin_is_en'] = ($lang === 'en') ? '1' : '';
        $tpl->vars['admin_is_ru'] = ($lang === 'ru') ? '1' : '';
        $tpl->vars['admin_is_fr'] = ($lang === 'fr') ? '1' : '';
        $tpl->vars['admin_url_en'] = $origin . '/miban/';
        $tpl->vars['admin_url_ru'] = $origin . '/ru/miban/';
        $tpl->vars['admin_url_fr'] = $origin . '/fr/miban/';
    }

    
    
    private function Activate() {
        // 15d4a01d277e3a48d341d94bd386c819c81076825e1b902138f868e871e271fd
        $hash = _REQUEST('h');
        $Member = new AdminMember(null);
        $this->contents = '';
        try {
            $props = $Member->isActivationRequired($hash);
            if ($props==false) {
                throw new EPageError(EPageError::PAGE_NOT_FOUND);
            }
        } catch (EPageError $ex) {    
            $msg = $ex->getMessage();
            $this->MainTpl->vars['message'] = $this->translateError($msg);
            return;
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            $this->MainTpl->vars['message'] = $this->translateError($msg);
            return;
        }
        
        $data = array();
        if (_POSTINT('cmd') === 1) {
            $data['name'] = _POST('aname');
            $data['passa'] = _POST('passa');
            $data['passb'] = _POST('passb');
            $data['captcha'] = _POST('fb_code');
            
            try {
                
                $ok = $Member->Edit($data);
                if ($ok ===true) {
                    // redirect to main page
                    $validator = new UserValidator();
                    $validated = $validator->ValidateUser($Member->getProp('usr_name'), $data['passa'], '');
                    if ($validated) {
                          $identity = $validator->Create();
                          HttpContext::current()->SetIdentity($identity);
                          HttpContext::current()->request()->AppName = 'Master';
                          HttpContext::current()->request()->Voider = '';
                    }
                    return;
                } else {
                    throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
                }
            } catch (Exception $ex) {
                $msg = $ex->getMessage();
                $this->MainTpl->vars['message'] = $this->translateError($msg);
            }
        }
        
        $tpl = new Template('adminmembers/activation.inc');
        $tpl->vars['hash'] = $hash;
        $tpl->vars['member'] = $props;
        $tpl->vars['input'] = $data;
        $this->contents = $tpl->Render();
    }
    
    
    
    
    
    private function translateError($message) {
        $msg = $message;
        return $msg;
    }

}

?>