<?php
    
    class PagesBlock extends DataAdapter {

        // must override
        protected $table = '#__pageblocks'; // ' #__table'
        protected $idField = 'name';
        protected $FieldArray = Array('name','displaytext', 'pos', 'description');
        
    }

?>
