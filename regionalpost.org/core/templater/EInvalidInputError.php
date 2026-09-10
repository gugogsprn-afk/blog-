<?php

    class EInvalidInputError extends EPageError {

        public $InvalidFields = array();
        
        public function __construct($fieldList,$message='') {
            parent::__construct(self::INVALID_INPUT,$message);
            $this->InvalidFields = $fieldList;
        }
        
       

    }

?>
