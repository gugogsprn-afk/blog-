<?php
    if (isset($_REQUEST['_SESSION'])) die("error opening file");
        
    define('ROOTDIR', dirname(realpath(__FILE__)).'/');
    define('BASEHREF', '/'.basename(dirname(__FILE__))); // set to root eg. '/'
    $BASEDIR = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
    // Treat filesystem root / Windows root / "." as site root (empty prefix).
    // Leaving BASEDIR as "/" made baseAddress end with "/" and produced // in OG URLs.
    if ($BASEDIR === '\\' || $BASEDIR === '/' || $BASEDIR === '.' || empty($BASEDIR)) {
        $BASEDIR = '';
    }
    define('BASEDIR', $BASEDIR);
    
    require_once ROOTDIR.'core/kernel/HttpRuntime.php';
    require_once ROOTDIR.'_globalmodule.php';
   
    HttpRuntime::BuildRuntime();
    
    
   


// <!-- Т -->
?>