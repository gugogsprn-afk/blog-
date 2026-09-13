<?php
// changed    
    class SlideItem extends TableEditableItem {

         
        protected $table = '#__slides';
        protected $FieldArray = Array('id', 'name', 'descr', 'url', 'pos', 'visible','name_lg1', 'descr_lg1','name_lg2', 'descr_lg2');
        protected $CollectionFieldArray = Array('id', 'name', 'descr', 'url', 'pos', 'visible');
        protected $ValidationFields = Array('name'=>'!null');
        
        
        public function InsertFromOv($itemProps) {
            parent::InsertFrom($itemProps, null);
            if (empty($this->ID))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating slide');
            return true;
        }
        
        
        public function UpdateFromOv($itemProps) {
           parent::UpdateFrom($itemProps);
           if (empty($this->ID))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error updating slide');
            return true;
        }
       
        public function Remove() {
            $ok = parent::Remove();
            return $ok;
        }
        

    }

?>
