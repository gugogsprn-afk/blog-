<?php


class HttpRuntime {

    private static $runtimeReady=false;
    private static $classMap;


    public static function BuildRuntime() {
        if (self::$runtimeReady) return;
        $cm = array();
        require_once ROOTDIR."core/kernel/_classmap.php";
        require_once CURPATH.'core/kernel/_touchlist.php';
        self::$classMap = $cm;
        spl_autoload_register(array(__CLASS__, 'loader'));
        self::$runtimeReady=true;
    }


    public static function AppendTouch($arr) {
        self::$classMap = array_merge(self::$classMap, $arr);
    }




    private static function loader($className) {
        $slashPos = strpos($className, '\\');

        if ($slashPos!==false) {
            $nameSpace = substr($className, 0, $slashPos);
            $localDir = self::$classMap['plugins'][$nameSpace];
            $relativeClass = substr($className, $slashPos+1);
            $file = $localDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists(ROOTDIR.$file)) {
                require_once ROOTDIR.$file;
                return;
            }
        }

        $underlinePos = strpos($className, '_');
        if ($underlinePos!==false) {
            $nameSpace = substr($className, 0, $underlinePos);
            if ($nameSpace==='Google') {
                $localDir = self::$classMap['plugins']['Google_'];
                $relativeClass = substr($className, $underlinePos+1);
                $file = $localDir . str_replace('_', '/', $relativeClass) . '.php';
                if (file_exists(ROOTDIR.$file)) {
                    require_once ROOTDIR.$file;
                    return;
                }
            }
        }

        $localDir = self::$classMap[$className];
        if (file_exists(ROOTDIR.$localDir.$className . '.php'))
            require_once ROOTDIR.$localDir.$className . '.php';
    }

}


//<!--Т-->
?>
