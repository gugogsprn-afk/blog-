<?php
    
    class ComponentType extends DataAdapter {

        // must override
        protected $table = '#__componenttypes';
        protected $idField = 'name';
        protected $FieldArray = Array('name', 'displaytext', 'linktype','itemloc','selectable');
        
        public function __construct($mId) {
            parent::__construct($mId);
        }
        
        

    }

?>
