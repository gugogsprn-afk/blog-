<?php
 
    class DatabaseProvider {
        
        
       const DBLIB = 'Mysqli'; // Mysql || Mysqli
        
       private static $instance;
        
        
       private function __construct() {
            
       }
       
       /**
        * 
        * @return MysqliDatabaseProxy
        */
       public static function provide() {
            if (!isset(self::$instance)) {
                $c = self::DBLIB.'DatabaseProxy';
                self::$instance = new $c();
                self::$instance->setSuffix(HttpContext::current()->culture()->Suffix());
            }
            return self::$instance;
       }
       
       
      
 
        
        
    }
    
   
//<!--Т-->
?>