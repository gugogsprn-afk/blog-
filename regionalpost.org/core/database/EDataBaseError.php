<?php
 

    class EDataBaseError extends Exception {
        
        public $ErrorNum = 0;

      public function __construct($ErrNum,$message){
        parent::__construct('DB Error: '.$ErrNum.':'.$message);
        $this->ErrorNum = $ErrNum;
      }
  
   }
    
   
//<!--?-->
?>