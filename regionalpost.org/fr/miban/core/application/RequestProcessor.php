<?php

    class RequestProcessor {

        const RESPONSE_NORMAL = 'normal';
        const RESPONSE_BLOCK = 'block';
        const RESPONSE_JSON = 'json';
        const RESPONSE_TEXT = 'text';
        const RESPONSE_JS = 'js';

        
        private $responseMethod = 'normal';
        
        private $AppList = array('filebrowser','helpers','catalog','adminmembers','config','pages','product','slides','adprods','banners','orders','bookings','videogallery','itemmenu','members');
        
        public $siteBaseAddress = '';
		
        public $baseAddress = '';
        public $reletiveUrl = '';
        public $fullUrl = '';
        public $langIdentifier;
        public $AppName = '';
        public $Voider = '';
        
        public $Permission = '';

        
        public function __construct() {
                
        }
        
        // called before create db instance
        public function Initilize() {
                        
            $suff =trim(BASEDIR);
            if (!empty($suff) && $suff!='/')
                $suff = $suff.'/';
            
            
            
            $this->siteBaseAddress = (( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://').
                                 (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));
            $this->baseAddress =$this->siteBaseAddress.$suff;
            
            $suff =trim(BASEHREF);
            if (!empty($suff) && $suff!='/')
                $suff = $suff.'/';
            
            $this->siteBaseAddress = $this->siteBaseAddress.  str_replace("/admin", "", BASEDIR).'/';
            $this->langIdentifier = $this->detectAdminLang();
        }
        
        
        // called after DB instance
        public function Handle() {
            
            $this->AppName = 'Master';
            $this->Voider = '';
            $this->reletiveUrl = '';
            $this->Permission = 'r';
            $this->langIdentifier = $this->detectAdminLang();
            
            
            if (isset($_REQUEST['show']) && !empty($_REQUEST['show']) && $_REQUEST['show']!=='index.php') {
                $this->AppName = strtolower(_REQUEST('show'));
                $this->Voider = ucfirst(_REQUEST('voider'));
                $this->reletiveUrl ='index.php?show=' . $this->AppName.'&voider='.$this->Voider;
                // TODO: implement permission
                //$this->Permission = $currentUrl->getProp('perm');
            }
            
            $this->fullUrl = $this->baseAddress.$this->reletiveUrl;
           
            
            if (!in_array(strtolower($this->AppName), $this->AppList)) 
                unset ($this->AppName);
        }
        
        
        public function isCached() {
            return false;
        }
        
        
        public function setResponseMethod($value) {
            $this->responseMethod = $value;
        }
        
        public function IsAjax() {
            $bl = ($_REQUEST['X-Requested-With'] == 'XMLHttpRequest' || @$_SERVER["HTTP_X_REQUESTED_WITH"] || $_REQUEST['response'] == 'block' || $_REQUEST['response'] == 'json' || $this->responseMethod!==RequestProcessor::RESPONSE_NORMAL);
            Return $bl;
        }
        
        public function IsJson() {
            if ($this->responseMethod===RequestProcessor::RESPONSE_JSON)
                return true;
            $acceptType = $_SERVER["HTTP_ACCEPT"];
            $bl = $_REQUEST['response']==='json' || strpos($acceptType, 'application/json')!==false;
            return $bl;
        }
        
        public function IsText() {
            return $this->responseMethod === RequestProcessor::RESPONSE_TEXT;
        }
        
        public function isJS() {
            return $this->responseMethod === RequestProcessor::RESPONSE_JS;
        }

        private function detectAdminLang() {
            $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
            if (strpos($script, '/ru/miban') !== false) {
                return 'ru';
            }
            if (strpos($script, '/fr/miban') !== false) {
                return 'fr';
            }
            return 'en';
        }
        
    }

//<!--Т-->
?>