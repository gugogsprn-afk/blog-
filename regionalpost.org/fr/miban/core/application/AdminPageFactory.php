<?php

    class AdminPageFactory {
        
        
        public function __construct() {
            
        }
        
        
        public function CreatePage() {
            if (!HttpContext::current()->request()->AppSet)
                return new AdminMasterWebPage();
                
            $c =HttpContext::current()->request()->AppName.'WebPage';
            if (class_exists($c)) {
                return new $c;
            } else {
                throw new EPageError(EPageError::PAGE_NOT_FOUND);
            }
            
        }
        
        
        
        
        
        
    }


?>