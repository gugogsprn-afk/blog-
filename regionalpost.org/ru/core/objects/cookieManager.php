<?php

    class cookieManager implements Countable {
        
        private $lifeTime = 86400;
        private $limit = 4096;
        
        private $group = '';
        
        private $path = '';
        
        private $items = array();

        public static function writeCookie($name,$value) {
            setcookie($name, $value, time()+86400 * 30,'/','',false,true);
        }
        
        public static function loadCookie($name){
            return $_COOKIE[$name];
        }


        public static function isCookiesEnabled() {
            return $_SESSION['cookiesEnabled']===2;
        }
        
        public static function writeTestCookie() {
            setcookie('tc', 1, time()+3600);
        }
        
        public static function checkTestCookie() {
            $bl = false;
            if(!empty($_COOKIE['tc'])) {
                $_SESSION['cookiesEnabled']=2;
                $bl = true;
            } else {
                $_SESSION['cookiesEnabled']=1;
            }
            unset($_COOKIE['tc']);
            setcookie('tc', null, -1);
            return $bl;
        }

        public function __construct($cookieGroup,$path='') {
            $this->group = $cookieGroup;
            $this->lifeTime = 86400 * 30;
            $this->path = $path;
            if ($path!=='current' && !empty($path)) {
                $this->path = BASEDIR.'/'.$path;
            } elseif (empty($path)) {
                $this->path = '/';
            }
            $this->items = $this->Load();
        }
        
        public function setLifeTime($days) {
            $this->lifeTime = 86400*intval($days);
        }
        
        
        public function setCookie($name,$value) {
            $this->items[$name] = $value;
            $this->items = array_filter($this->items);
            $bl = $this->Save();
            return $bl;
        }
        
        public function RemoveCookie($name) {
            $this->items[$name] = null;
            unset($this->items[$name]);
            $bl = $this->Save();
            return $bl;
        }
        
        
        
        public function getCookie($name) {
            return $this->items[$name];
        }
        
        
        public function toArray() {
            return $this->items;
        }

        public function count() {
            return count($this->items);
        }

        
        private function Load() {
            $arr = array();
            try {
                if (!empty($_COOKIE[$this->group])) {
                    $arr = (array) DeserilizeJson($_COOKIE[$this->group]);
                }
            } catch (Exception $ex) {
                //return false;
            }
            return $arr;
        }
        
        private function Save() {
            $ok = false;
            try {
                $cookie_data = SerilizeToJson($this->items);
                if (mb_strlen($cookie_data)>$this->limit) {
                    throw new Exception('Արժեքի մեծությունը գերազանցում է թույլատրելին');
                }
                //setcookie($this->group, '', time()-3600 , '/');
                if ($this->path==='current') {
                    $ok = setcookie($this->group,$cookie_data, time() + $this->lifeTime);
                } else {
                    $ok = setcookie($this->group,$cookie_data, time() + $this->lifeTime,$this->path,'',false,true);
                }
                
            } catch (Exception $ex) {
                $ok = false;
            }
            return $ok;
        }
        
        
    }

?>
