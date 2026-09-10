<?php

    class RemoteRequest {
        
        private $url='';
        
        public $TimeOut = 20;
        public $PassUserAgent = false;
        public $headers = array();

        private $status = 400;
        private $response = '';
        private $error = '';
        
        public function Status() {
            return $this->status;
        }
        
        public function getResponse() {
            return $this->response;
        }
        
        public function getError() {
            return $this->error;
        }

        public function __construct($RemoteUrl) {
            $this->url = $RemoteUrl;
        }
        
        function Create($params,$Method = 'POST') {
            $userAgent = _VAR($_SERVER['HTTP_USER_AGENT']);
            $data_string = '';
            if (array_isset($params)) {
                $data_string = http_build_query($params);
                $ReqUrl = $this->url;
                if ($Method == 'GET')
                        $ReqUrl = $ReqUrl.'?'.$data_string;
            }
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $ReqUrl);  
            curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->TimeOut);
            /*
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSLCERT, getcwd().$_conf['arca_cert']);
             * 
             */
            if ($this->PassUserAgent)
                curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            if (count($this->headers)>0) 
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
            /*
            if (!empty($_conf['arca_curl_proxy'])) 
                curl_setopt($ch,CURLOPT_PROXY,$_conf['arca_curl_proxy']); 
             * 
             */
            if ($Method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string );
            }
            
            $this->status = 400;
            $this->response = '';
            $this->error = '';
            
            $resp = false;
            
            try {
                $resp = curl_exec($ch);
            } catch (Exception $ex) {
                $this->status = 400;
                $this->error = $ex->getMessage();
            }
            
            if( ! $resp)  { 
                $this->status = 400;
                $this->error = curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $this->status = $httpCode;
                if($httpCode != 200) {
                    $errMessage = explode("\n", $resp);
                    $this->error = $errMessage;
                    $this->response = '';
                } else {
                    $list = explode("\r\n\r\n",$resp); 
                    $this->status = 200;
                    $this->error = '';
                    $this->response = $list[count($list)-1];
                }
            }
            curl_close($ch);
            
            return $this->status;
        }
        
        
        public function CreateStreamed($params,$Method = 'POST') {
            $userAgent = _VAR($_SERVER['HTTP_USER_AGENT']);
            
            
            $ReqUrl = $this->url;
            
            if (array_isset($params)) {
                $data_string = http_build_query($params);
                if ($Method === 'GET')
                    $ReqUrl = $ReqUrl.'?'.$data_string;
            }
            
            $PassHeaders = implode("\r\n", $this->headers) . "\r\n";
            
            $HttpParams = array(
                'ignore_errors' => true,
                'method' => $Method
                );
            if (count($this->headers))
                $HttpParams['header'] = $PassHeaders;
            if ($Method ==='POST')
                $HttpParams['content'] = $data_string;
            
            $result = file_get_contents($ReqUrl, false, stream_context_create(array('http' => $HttpParams)));
            
            $return = array();
            $this->status = substr($http_response_header[0], strpos($http_response_header[0], ' ')+1);
            $this->response = $result;
            return $this->status;    
            
        }
        
        public static function rest_Request($url, $params = null, $verb = 'GET') {
            $cparams = array(
                'http' => array(
                    'method' => $verb,
                    'ignore_errors' => true,
                    'user_agent'=> $_SERVER['HTTP_USER_AGENT'] 
                )
            );
            if ($params !== null) {
                $params = http_build_query($params);
                if ($verb == 'POST') {
                    $cparams['http']['content'] = $params;
                } else {
                    $url .= '?' . $params;
                }
            }

            $context = stream_context_create($cparams);
            $fp = fopen($url, 'rb', false, $context);
            if (!$fp) {
                $res = false;
            } else {
                // If you're trying to troubleshoot problems, try uncommenting the
                // next two lines; it will show you the HTTP response headers across
                // all the redirects:
                // $meta = stream_get_meta_data($fp);
                // var_dump($meta['wrapper_data']);
                $res = stream_get_contents($fp);
            }

            if ($res === false) {
                throw new Exception("$verb $url failed: $php_errormsg");
            }

            
            return $res;
        }
        
        public static function CurlRequest($url) {
            $headers = array(
                    "Connection: keep-alive",
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8", 
                    "Accept-Language: en-US,en;q=0.8,ru;q=0.6",
                    "Host: www.domain.com",
                    "Proxy-Connection: keep-alive"
            );
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url);  
            curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
            //curl_setopt($ch, CURLOPT_AUTOREFERER, true );

            if (count($headers)>0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            $status = 400;
            $response = '';
            $error = '';

            $resp = false;

            try {
                $resp = curl_exec($ch);
            } catch (Exception $ex) {
                $status = 400;
                $error = $ex->getMessage();
            }

            if( ! $resp)  { 
                $status = 400;
                $error = curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $status = $httpCode;
                if($httpCode != 200) {
                    $errMessage = explode("\n", $resp);
                    $error = $errMessage;
                    $response = '';
                } else {
                    $list = explode("\r\n\r\n",$resp); 
                    $status = 200;
                    $error = '';
                    $response = $list[count($list)-1];
                }
            }
            curl_close($ch);
            /*
            echo 'Status :' .$status.'<br>';
            echo '<pre>';
            var_dump($error);
            echo '</pre>';
            */
            

            return $response;
        }
        
        
        public static function CurlRequestPOST($url, $postData) {
            $headers = array(
                    "Connection: keep-alive",
                    "Proxy-Connection: keep-alive",
                    "Content-type: application/x-www-form-urlencoded"
            );
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url);  
            curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData) );
            
            //curl_setopt($ch, CURLOPT_AUTOREFERER, true );

            if (count($headers)>0) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            $status = 400;
            $response = '';
            $error = '';

            $resp = false;

            try {
                $resp = curl_exec($ch);
            } catch (Exception $ex) {
                $status = 400;
                $error = $ex->getMessage();
            }

            if( ! $resp)  { 
                $status = 400;
                $error = curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $status = $httpCode;
                if($httpCode != 200) {
                    $errMessage = explode("\n", $resp);
                    $error = $errMessage;
                    $response = '';
                } else {
                    $list = explode("\r\n\r\n",$resp); 
                    $status = 200;
                    $error = '';
                    $response = $list[count($list)-1];
                }
            }
            curl_close($ch);
            /*
            echo 'Status :' .$status.'<br>';
            echo '<pre>';
            var_dump($error);
            echo '</pre>';
            */
            

            return $response;
        }
    }

?>
