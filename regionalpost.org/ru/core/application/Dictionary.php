<?php

    /**
     * Description of Configuration
     *
     * @author Sight©
     */
    class Dictionary {

        public function __construct() {
            
        }
        
        private static $ItemCache = array();
        
        private static $cachingEnabled=true;
        
        private static $allLoaded = false;

        public static function Load($name) {
            if (self::$cachingEnabled && array_key_exists($name,self::$ItemCache))
                return self::$ItemCache[$name];
            $DB = DatabaseProvider::provide();
            $conf = $DB->Scalar("SELECT words_# as word FROM #__dictionary WHERE idkey='".$name."'");
            if ($conf===false) return null;
            if (self::$cachingEnabled)
                self::$ItemCache[$name] = $conf;
            return $conf;
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
            $confList = $DB->Query("SELECT idkey,words_# as word FROM #__dictionary WHERE idkey IN (".$valueList.")");
            
            if ($DB->RowCount()>0) {
                while ($conf = $DB->ReadRow()) {
                    $value = $conf['word'];
                    $result[$conf['idkey']] = $value;
                    if (self::$cachingEnabled)
                        self::$ItemCache[$conf['idkey']] = $value;
                }
            }
            return $result;
        }
        
        public static function loadAll() {
            $DB = DatabaseProvider::provide();
            
            if (self::$cachingEnabled && self::$allLoaded) {
                return self::$ItemCache;
            }
            
            $result = array();
            $DB->Query("SELECT idkey,words_# as word FROM #__dictionary");
            if ($DB->RowCount()>0) {
                while ($conf = $DB->ReadRow()) {
                    $value = $conf['word'];
                    $result[$conf['idkey']] = $value;
                    if (self::$cachingEnabled)
                        self::$ItemCache[$conf['idkey']] = $value;
                }
            }
            if (self::$cachingEnabled)
                self::$allLoaded = true;
            return $result;
            
        }
            

    }

?>
