<?php

    /**
     * 
     *
     * @author Sight©
     */
    class Role extends DataAdapter {

        protected $table = '#__roles';
        protected $idField = 'id';
        protected $FieldArray = Array('id', 'name', 'descr', 'p_config', 'p_members', 'p_roles','p_link', 'p_category', 'p_item', 'p_file', 'p_search','c_category');
        protected $CollectionFieldArray = Array('id', 'name', 'descr');
        protected $ValidationFields = Array('name' => '!null');
        
        

        protected function OnLoad($success) {
            if ($success) {
                if ($this->IsCollectionItem) {
                    
                } else {
                    $composite = $this->getProp('c_category');
                    $value = trim($composite);
                    $combined = array();
                    if (!empty($value)) {
                        $combined = DeserilizeJson($value);
                    }
                    $this->setProp('category', $combined);
                }
            }
        }
        
        
      
       public function Add($item) {
           $this->bindData($item);
           parent::Insert();
       }
        
        
        public function Edit($item) {
            $this->bindData($item);
            $ok = parent::Update();
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
        }
        
        public function Remove() {
            /*$db = DatabaseProvider::provide();
           
            $childCount = _VARINT($db->Scalar("SELECT COUNT(*) FROM #__files WHERE itemtype='items' AND parent_id = ".$this->ID));
            if ($childCount>0) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Article contains files');
            */         
            parent::Delete();
            return true;
        }
       
        /*         * * permissions ** */

        public static function AllPermissions($flatArray = false,$currentPerms = array()) {
            $DB = DatabaseProvider::provide();
            $query = 'SELECT tp.id, tp.parent_id, tp.name as name, tp.types,tg.name as parent_name,tp.alias,tp.permtype
                            FROM #__perms tp
                            INNER JOIN #__permgroups tg ON tp.parent_id = tg.id
                            ORDER BY tg.pos,tp.pos';
            
            if ($flatArray===true) {
                return $DB->Fill($query,'id');
            }
            
            $ok = $DB->Query($query);
            $permList = array();
            if ($ok && $DB->RowCount() > 0) {
                while ($row = $DB->ReadRow()) {
                    $parentID = intval($row['parent_id']);
                    if ($row['permtype']!=='p')                        continue;
                    if (strpos($row['types'], 'r')!==false) $row['ar'] = 'true';
                    if (strpos($row['types'], 'w')!==false) $row['aw'] = 'true';
                    if (strpos($row['types'], 'e')!==false) $row['ae'] = 'true';
                    if (strpos($row['types'], 'd')!==false) $row['ad'] = 'true';
                    
                    /*
                    1248
                    rwed
                    */
                    if (array_key_exists('p_'.$row['alias'], $currentPerms)) {
                        $cValue = intval($currentPerms['p_'.$row['alias']]);
                        if (($cValue & 1)===1) $row['arc'] = 'checked="checked"';
                        if (($cValue & 2)===2) $row['awc'] = 'checked="checked"';
                        if (($cValue & 4)===4) $row['aec'] = 'checked="checked"';
                        if (($cValue & 8)===8) $row['adc'] = 'checked="checked"';
                    }
                    
                    if (array_key_exists($parentID, $permList)) {
                        $permList[$parentID]['items'][] = $row;
                    } else {
                        $c = array();
                        $c['parent'] = $row['parent_name'];
                        $c['items'] = array();
                        $c['items'][] = $row;
                        $permList[$parentID] = $c;
                    }
                }
            }
            return $permList;
        }
        
        public static function getCompositeEditors($currentPerms,$catlist) {
            $editors = array();
            if (array_key_exists('category', $currentPerms)) {
                foreach ($currentPerms['category'] as $catID=>$perm) {
                    $row = $catlist[_VARINT($catID)];
                    $cValue = intval($perm);
                    if (($cValue & 1)===1) $row['arc'] = 'checked="checked"';
                    if (($cValue & 2)===2) $row['awc'] = 'checked="checked"';
                    if (($cValue & 4)===4) $row['aec'] = 'checked="checked"';
                    if (($cValue & 8)===8) $row['adc'] = 'checked="checked"';
                    $editors[] = $row;
                }
            }
            return $editors;
        }

        
        }

?>