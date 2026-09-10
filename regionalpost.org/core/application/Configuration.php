<?php

    /**
     * Description of Configuration
     *
     * @author Sight©
     */
    class Configuration {

        public function __construct() {
            
        }
        
        private static $ItemCache = array();
        
        private static $cachingEnabled=true;

        public static function Load($name) {
            if (self::$cachingEnabled && array_key_exists($name,self::$ItemCache))
                return self::$ItemCache[$name];
            $DB = DatabaseProvider::provide();
            $conf = $DB->Fetch("SELECT value,type FROM #__conf WHERE name='".$name."'");
            if ($conf===false) return null;
            $value = null;
            switch ($conf['type']) {
                case 'number':
                    $value = (is_numeric($conf['value'])?$conf['value']:0);
                    break;
                case 'image':
                    list($mode,$wh) = explode(';', $conf['value']);
                    list($w,$h) = explode('x',$wh);
                    $value = array('mode'=>$mode,'w'=>$w,'h'=>$h);
                    break;
                case 'trans':
                    $vles = array();
                    list($vles['val'],$vles['val_lg1'],$vles['val_lg2']) = explode('|', $conf['value']);
                    $value = $vles['val'.HttpContext::current()->culture()->Suffix()];
                    break;
                default:
                    $value = $conf['value'];
                    break;
            }
            if (self::$cachingEnabled)
                self::$ItemCache[$name] = $value;
            return $value;
        }
        
        public static function BulkLoad($items) {
            $DB = DatabaseProvider::provide();
            
            $result = array();
            $arr = array();
            foreach($items as $value) {
                $value = $DB->EscapeValue($value);
                if (self::$cachingEnabled && array_key_exists($value, self::$ItemCache)) {
                   $result[$value] = self::$ItemCache[$value];
                   continue;
                } 
                $arr[] = $value;
            }
            if (count($arr)<=0) return $result;
            $valueList = implode(',', $arr);
            $confList = $DB->Query("SELECT name,value,type FROM #__conf WHERE name IN (".$valueList.")");
            
            if ($DB->RowCount()>0) {
                while ($conf = $DB->ReadRow()) {
                    $value = null;
                    switch ($conf['type']) {
                        case 'number':
                            $value = (is_numeric($conf['value'])?$conf['value']:0);
                            break;
                        case 'image':
                            list($mode,$wh) = explode(';', $conf['value']);
                            list($w,$h) = explode('x',$wh);
                            $value = array('mode'=>$mode,'w'=>$w,'h'=>$h);
                            break;
                        case 'trans':
                            $vles = array();
                            list($vles['val'],$vles['val_lg1'],$vles['val_lg2']) = explode('|', $conf['value']);
                            $value = $vles['val'.HttpContext::current()->culture()->Suffix()];
                            break;
                        default:
                            $value = $conf['value'];
                            break;
                    }
                    $result[$conf['name']] = $value;
                    if (self::$cachingEnabled)
                        self::$ItemCache[$conf['name']] = $value;
                }
            }
            return $result;
        }
        
        

    }

?>
