<?php

    class EPageError extends Exception {

        const NONE = 'Unknown error';
        const PAGE_NOT_FOUND = 'Page not found';
        const INTERNAL_SERVER_ERROR = 'Internal server error';
        const TEMPLATE_ERROR = 'Template error';
        const INVALID_INPUT = 'Invalid input';
        const CUSTOM_ERROR = 'Error';
        const METHOD_NOT_FOUND = 'Requested method not found';
        const SECURITY_ERROR = 'Security error';

        public $errType = self::NONE;
        
        private $addDatas = null;

        public function __construct($Err, $message = '',$addData = null) {
            $this->errType = $Err;
            if ($Err === EPageError::CUSTOM_ERROR) {
                parent::__construct($message);
            } else {
                $msg = $this->getTMessage();
                $message = empty($message)?$msg:$message;
                $message = empty($message)?$this->getMessage():$message;
                parent::__construct($message);    
            }
            $this->addDatas = $addData;
        }
        
        private function getTMessage() {
            $ret = '';
            switch ($this->errType) {
                case EPageError::INTERNAL_SERVER_ERROR:
                    $ret = 'Internal server error';
                    break;
                case EPageError::INVALID_INPUT:
                    $ret = 'Input data is invalid';
                    break;
                case EPageError::METHOD_NOT_FOUND:
                    $ret = 'The requested method could not be found';
                    break;
                case EPageError::PAGE_NOT_FOUND:
                    $ret = 'The requested page could not be found';
                    break;
                case EPageError::SECURITY_ERROR:
                    $ret = 'Access is denied';
                    break;
                case EPageError::TEMPLATE_ERROR:
                    $ret = 'Template parsing error';
                    break;
                default:
                    $ret = 'Unknown error';
                    break;
            }
            return $ret;
        }
        
        public function getData() {
            return $this->addDatas;
        }

    }

?>