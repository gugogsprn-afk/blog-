<?php

abstract class WebPageBase {
    
    
    public function __construct() {
        
    }
    
    // TODO: implement this method and identity permission methods
    public function checkPermissions() {
        //return HttpContext::current()->Identity()->HasPermission(_APPNAME(),HttpContext::current()->request()->Permission);
    }
    
    public function preVoid() { 
        return false; 
    }
    
    public abstract function Index();
    
    public abstract function Display();
    
    public function InvokeMethod($methodName) {
        if (empty($methodName)) {
            $this->Index();
            return;
        }
        if (!method_exists($this, $methodName)) {
            throw new EPageError(EPageError::METHOD_NOT_FOUND);
        }
        $this->$methodName();
    }
    
    

}

?>