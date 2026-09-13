<?php
    
class HttpApplication {
    
   
    private $started = false;
    
    public function __construct() {
        //
    }
    
    public function start() {
        
        date_default_timezone_set('Etc/GMT+0');
        
        session_cache_limiter('public');
        session_start();
        
        mb_internal_encoding("UTF-8");
        
        mb_regex_encoding("UTF-8");
       
        
        $this->SessionTouch();
         
      	$ctx = HttpContext::current();
        
        HttpContext::current()->InitilizeContext();
        
        $validator = new UserValidator();
        $identity = $validator->Create();
        HttpContext::current()->SetIdentity($identity);
        $this->started = !HttpContext::current()->request()->isCached();
        
        
    }

    private function SessionTouch() {
            //30 min
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
                session_unset();
                session_destroy(); 
            }
            $_SESSION['LAST_ACTIVITY'] = time();
            // session started more than 30 minutes ago (disable recycle session)
            //if (!isset($_SESSION['CREATED'])) {
            //    $_SESSION['CREATED'] = time();
            //} else if (time() - $_SESSION['CREATED'] > 1800) {
            //    session_regenerate_id(true);
            //    $_SESSION['CREATED'] = time();
            //}
    }
    
    public function isStarted() {
        
        return $this->started;
    }
    

    public function end() {
        DatabaseProvider::provide()->Dispose();
        
        // close database connection
        // start garbage collector (dispose managed object from memmory)
    }
    
    
    public function onError($errType,$message,$params) {
        // TODO: log error?
        $page = new ErrorWebPage($errType,$message,$params);
        $page->Initilize();
        $page->Index();
        $page->Display();
    }

}

   
//<!--Т-->
?>