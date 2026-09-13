<?php
    
    class ConfigurationGroup extends DataAdapter {

        // must override
        protected $table = '#__confgroups'; // ' #__table'
        protected $FieldArray = Array('id', 'name', 'pos');
        
        public $items = array();
        


        public function LoadChilds() {
            $itemTemplate = new ConfigurationItem(null);
            $itemTemplate->setProp('group_id', $this->ID);
            $Collection = $itemTemplate->Collection();
            $Collection->ordering = 'pos';
            $Collection->Load();
            $this->items = $Collection->toArray();
        }
        

    }

?>
