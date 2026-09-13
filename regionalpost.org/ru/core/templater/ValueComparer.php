<?php

    class ValueComparer {

        private function __construct() {
            
        }
        
        public static function compare($token,$value,$cond) {
            if (empty($cond)) {
                if (empty($value)) return false;
                return true;
            }
            $bl = true;
            if (substr($cond,0,1)=='!') {
                $cond = substr($cond,1);
                $bl = false;
            }
            $res = false;
            if ($cond == 'null') {
               $res = !isset($value) || $value=='';
            } elseif (substr($cond,0,1) == '>') {
               $res = isset($value) && $value>floatval(substr($cond,1)); 
            } elseif (substr($cond,0,1) == '<') {
               $res = isset($value) && $value<floatval(substr($cond,1)); 
            } elseif (substr($cond,0,1) == '=') {
               $res = isset($value) && $value==substr($cond,1); 
            } else {
               throw new ETemplateError('Wrong condition in IF statement : [ '.$token.$cond.' ]');  
            }
            if ($bl==true) {
                return $res;  } else { return !$res; };
        }
        

    }

?>
