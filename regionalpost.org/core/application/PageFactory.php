<?php

    class PageFactory {
        
        
        public function __construct() {
            
        }
        
        
        public function CreatePage($appName) {
            if (empty($appName))
                return new MasterWebPage();
            $c = $appName.'WebPage';
            if (class_exists($c)) {
                return new $c;
            } else {
                throw new EPageError(EPageError::PAGE_NOT_FOUND);
            }
            
        }
        
        
        
        
        
        
    }


?>