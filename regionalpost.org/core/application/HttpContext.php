<?php

class HttpContext {
    
    private static $instance;
    
    private $requestInstance = null;
    private $cultureInstance = null;




    public $IdentityThumbprint = 2; // guest
    private $identity = null;
    
    private $isIdentified = false;

    private function __construct() {
        //
    }
    
    public function InitilizeContext() {
        
        $this->requestInstance = new RequestProcessor();
        $this->requestInstance->Initilize();
        $this->cultureInstance = new CultureInfo($this->requestInstance->langIdentifier);
        $this->cultureInstance->setCurrency($this->requestInstance->currencyIdentifier);
        $this->requestInstance->Handle();
    }
    
    /**
     * 
     * @return HttpContext
     */
    public static function current() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function __clone()
    {
        trigger_error('Invalid method', E_USER_ERROR);
    }
    
    
    /**** Context ********/

    /**
     * 
     * @return RequestProcessor
     */
    public function request() {
        return $this->requestInstance;   
    }
    
    /**
     * 
     *  @return CultureInfo
     */
    public function culture() {
       return $this->cultureInstance; 
    }
    
    
    /**
     * 
     * @return Identity
     */
    public function Identity() {
        return $this->identity;    
    }
       
    
    public function SetIdentity($identity) {
        $this->identity = $identity;
        if ($identity !==null) {
            $this->isIdentified = true;
            $IdentInfo = $identity->getInfo();
            $identity->setProperties();
            $this->IdentityThumbprint = intval($this->memberBits[$IdentInfo['type']]);
        }
    }
    
    
    
    
    public function Identified() {
        return $this->isIdentified;
    }
    
                  
                        
    

}

   
//<!--Т-->
?>