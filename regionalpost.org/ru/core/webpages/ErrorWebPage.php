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
        //protected $MainTpl = null;
        //protected $contents = '';

        protected function getSlider() {
            return null;
        }

        protected function getHeader() {
            $tpl = parent::getHeader();
            return $tpl;
        }

        protected function getMetas() {
            return array(
                    'metas'=>array('robots'=>false,'title'=>'Error'),
                    'metalinks'=>array(),
                    'ogtags'=>array(),
                    'canonical'=>''
                );
        }


        public function preVoid() {
            return false;
        }

        public function __construct($errType, $message,$params=null) {
            parent::__construct();
            $this->errorType = $errType;
            $this->title = $errType;
            $this->message = $message;
            $this->params = $params;
            /*
            if ($this->errorType==='Invalid input') {
                $this->message = $message. " [ ". $params[0]." ]";
            }*/
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
            $errorTitle = Dictionary::Load($this->title);
            if (!empty($errorTitle)) {
                $this->title = $errorTitle;
            }
            $_conf = array();
            require ROOTDIR . "_conf.php";
            $showErrorDetails = $_conf['debug'];
            $isDisplay = false;
            $detailsIsInternal = false;
            $status = 200;
            if ($this->errorType === EPageError::PAGE_NOT_FOUND) {
                header('HTTP/1.1 404 Not Found', true, 404);
                $status = 404;
            } elseif ($this->errorType === EPageError::METHOD_NOT_FOUND) {
                header('HTTP/1.1 405 Method Not Allowed', true, 405);
                $status = 405;
            } elseif ($this->errorType === EPageError::INTERNAL_SERVER_ERROR) {
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                $status = 500;
                $detailsIsInternal = true;
            } elseif ($this->errorType === EPageError::INVALID_INPUT) {
                $status = 400;
                header('HTTP/1.1 400 Bad Request', true, 500);
                $isDisplay = true;
            } elseif ($this->errorType === EPageError::SECURITY_ERROR) {
                if ($this->params == ESecurityError::ACCESS_DENIED) {
                    $status = 403;
                    header('HTTP/1.1 403 Forbidden', true, 403);
                } else {
                    $status = 401;
                    header('HTTP/1.1 401 Unauthorized', true, 401);
                }
            } elseif ($this->errorType === EPageError::TEMPLATE_ERROR) {
                $status = 500;
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                $detailsIsInternal = true;
            } else {
                $status = 400;
                header('HTTP/1.1 400 Bad Request', true, 400);
                $isDisplay = true;
            }

            if (!empty($this->message)) {
                if ($this->message==='ERR_RECNOTFOUND') {
                    $status = 404;
                    header('HTTP/1.1 404 Not Found', true, 404);
                    $this->title = "Error";
                    $this->message = "Page not found";
                    $isDisplay = false;
                } else {
                    $iserror = substr($this->message, 0, 4);
                    if ($iserror==='ERR_') {
                        $errID = explode(' ',  mb_trim($this->message),2);
                        $this->message = Dictionary::Load(trim($errID[0]));
                        if (count($errID)>1) {
                            $this->message = $this->message." ".$errID[1];
                        }
                    }
                }
            }

            if ($showErrorDetails===false && $detailsIsInternal===true) {
                $this->message = "Internal Error";
            }

            if (HttpContext::current()->request()->IsAjax()) {
                $arr = array();
                $arr['success'] = false;
                $arr['error'] = $this->message;
                $arr['title'] = $this->title;
                $arr['status'] = $status;
                $arr['type'] = ($this->errorType === EPageError::SECURITY_ERROR?'security':'error');
                if ($this->errorType == EPageError::INVALID_INPUT) {
                    $arr['invalids'] = $this->params;
                }
                if ($this->params !== null) {
                    $arr['exdata'] = $this->params;
                }
                $this->contents = SerilizeToJson($arr);
                HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
            } else {
                if ($status===404) {
                    $this->MainTpl = new Template('404.inc');
                    $this->MainTpl->vars['title'] = '';
                    $this->MainTpl->vars['message'] = '';
                    $this->contents='';
                }else if ($isDisplay===false) {
                    $this->MainTpl = new Template('error.inc');
                    $this->MainTpl->vars['title'] = $this->title;
                    $this->MainTpl->vars['message'] = $this->message;
                    $this->contents='';
                }  else {
                    $tpl = new Template('error.inc');
                    $tpl->vars['title'] = $this->title;
                    $tpl->vars['message'] = $this->message;
                    $this->contents = $tpl->Render();
                }
            }
        }
    }



?>
