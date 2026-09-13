<?php

    class ArrayObjectExt {
        
        public static $RetCount = 0;
        public static $CallStack = '';
        
        
        public static function Retrive($arr,$path) {
            self::$RetCount = self::$RetCount+1;
            self::$CallStack = self::$CallStack.'<br>'.$path;
            $path = trim($path, '.');
            $value = $arr;
            $parts = explode('.', $path);
            foreach ($parts as $part) {
                if (isset($value[$part])) {
                    $value = $value[$part];
                } else {
                    return null;
                }
                
            }
            
            return $value;   
        }
        
        function get_val($array, $path) {
            for ($i = $array; $key = array_shift($path); $i = $i[$key]) {
                if (!isset($i[$key]))
                    return null;
            }
            return $i;
        }

        function set_val(&$array, $path, $val) {
            for ($i = &$array; $key = array_shift($path); $i = &$i[$key]) {
                if (!isset($i[$key]))
                    $i[$key] = array();
            }
            $i = $val;
        }
        
        
        private function &get_from_array($path, &$array) {
            $current =& $array;
            foreach(explode('.', $path) as $key) {
                $current =& $current[$key];
            }
            return $current;
        }
        
        private function testing($path,$array) {
            $temp = &$data;
            foreach($exploded as $key) {
                $temp = &$temp[$key];
            }
            $temp = $value;
            unset($temp);    
        }
        
    }


?>