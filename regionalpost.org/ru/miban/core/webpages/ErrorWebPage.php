<?php

    class ErrorWebPage extends MasterWebPage {

        private $errorType = '';
        private $message = '';
        private $title = '';
        private $params = null;
       
        /**
        *
        * @var Template
        */
        protected $MainTpl = null;
        protected $contents = '';
        
        public function __construct($errType, $message,$params=null) {
            parent::__construct();
            $this->errorType = $errType;
            $this->title = $errType;
            $this->message = $message;
            $this->params = $params;
            
        }
        
        public function Initilize() {
            $this->MainTpl = new Template('error.inc');
        }
        

         /*
         * 400 Bad Request
         * 401 Unauthorized
         * 403 Forbidden
         * 404 Not Found
         * 405 Method Not Allowed
         * 500 Internal Server Error
         */ 
        
        public function Index() {
            if ($this->errorType === EPageError::PAGE_NOT_FOUND) {
                header('HTTP/1.1 404 Not Found', true, 404);
            } elseif ($this->errorType === EPageError::METHOD_NOT_FOUND) {
                header('HTTP/1.0 405 Method Not Allowed', true, 405);
            } elseif ($this->errorType === EPageError::INTERNAL_SERVER_ERROR) {
                header('HTTP/1.0 500 Internal Server Error', true, 500);
            } elseif ($this->errorType === EPageError::INVALID_INPUT) {
                header('HTTP/1.0 400 Bad Request', true, 500);
            } elseif ($this->errorType === EPageError::SECURITY_ERROR) {
                if ($this->params == ESecurityError::ACCESS_DENIED) {
                    header('HTTP/1.0 403 Forbidden', true, 403);
                } else {
                    header('HTTP/1.0 401 Unauthorized', true, 401);
                }
            } elseif ($this->errorType === EPageError::TEMPLATE_ERROR) {    
                header('HTTP/1.0 500 Internal Server Error', true, 500);
            } else {
                header('HTTP/1.0 400 Bad Request', true, 400);
            }
            
            if (!empty($this->message)) {
                $iserror = substr($this->message, 0, 4);
                if ($iserror==='ERR_') {
                    $errID = explode(' ',  mb_trim($this->message),2);
                    $this->message = Dictionary::Load(trim($errID[0]));
                    if (count($errID)>1) {
                        $this->message = $this->message." ".$errID[1];
                    }
                }
            }
        
            if (HttpContext::current()->request()->IsAjax()) {
                $arr = array();
                $arr['error'] = $this->message;
                $arr['title'] = $this->title;
                $arr['type'] = ($this->errorType === EPageError::SECURITY_ERROR?'security':'error');
                if ($this->errorType == EPageError::INVALID_INPUT) {
                    $arr['invalids'] = $this->params;
                }
                $this->contents = SerilizeToJson($arr);
                HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
            } else {
                $this->MainTpl->vars['title'] = $this->title;
                $this->MainTpl->vars['message'] = $this->message;
                $this->contents='';
            }
            
        }

        

    }

?>