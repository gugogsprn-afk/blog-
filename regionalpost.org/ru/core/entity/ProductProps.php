<?php

    class ProductProps extends SiteEntity {

       protected $table = '#__itemprops';
       protected $idField = 'id';
       protected $FieldArray = array('id', 'parent_id','parentname', 'name', 'itemvalue', 'pos', 'classname', 'isfilterable');
       protected $CollectionFieldArray = array('id', 'parent_id','parentname', 'name', 'itemvalue', 'pos', 'classname', 'isfilterable');
       
       
       
       public static function loadLists($parent_ids) {
           $DB = DatabaseProvider::provide();
           $DB->Query("SELECT parent_id,name_# as name,itemvalue FROM #__itemprops WHERE visible=1 AND parent_id IN (".$parent_ids.") ORDER BY parent_id,name_#");
           $ret = array();
           while ($row = $DB->ReadRow()) {
               $row['itemvalue'] = intval($row['itemvalue']);
               if (array_key_exists($row['parent_id'], $ret)) {
                   $ret[$row['parent_id']][] = $row;
               } else {
                   $ret[$row['parent_id']] = array();
                   $ret[$row['parent_id']][] = $row;
               }
           }
           return $ret;
       }
       
       public static function loadListOption($parent_id,$order) {
           $DB = DatabaseProvider::provide();
           $DB->Query("SELECT parent_id,name_# as name,itemvalue FROM #__itemprops WHERE visible=1 AND parent_id = '".$parent_id."' ORDER BY ".$order);
           $ret = '';
           while ($row = $DB->ReadRow()) {
               $ret = $ret.'<option value="'.$row['itemvalue'].'">'.$row['name'].'</option>';
           }
           return $ret;
       }
       
       public static function loadListArray($parent_id,$order) {
           $DB = DatabaseProvider::provide();
           $DB->Query("SELECT parent_id,name_# as name,itemvalue FROM #__itemprops WHERE visible=1 AND parent_id = '".$parent_id."' ORDER BY ".$order);
           $ret = array();
           while ($row = $DB->ReadRow()) {
               $ret[] = $row;
           }
           return $ret;
       }
       
   
       public static function loadList($parent_id,$keyCol) {
           $DB = DatabaseProvider::provide();
           return $DB->Fill("SELECT id,name_#,itemvalue,classname FROM #__itemprops WHERE visible=1 AND parent_id=".$DB->EscapeValue($parent_id),$keyCol);
       }
        
        
       
    public static function Exists($parent_id,$itemValue) {
        $DB = DatabaseProvider::provide();
        $id = $DB->Scalar("SELECT id FROM #__itemprops WHERE visible=1 AND parent_id=".$DB->EscapeValue($parent_id)." AND itemvalue="._VARINT($itemValue));
        return intval($id) > 0;
        
    }
    
    
    public static function GetItemAsArr($parent_id,$itemValue) {
        $DB = DatabaseProvider::provide();
        $row = $DB->Fetch("SELECT id,name_# as name,parentname_# as parentname FROM #__itemprops WHERE visible=1 AND parent_id=".$DB->EscapeValue($parent_id)." AND itemvalue="._VARINT($itemValue));
        if (!array_isset($row))
            return null;
        
        if (intval($row['id'])<=0)
            return null;
        
        return $row;
    }
    
        
       
   }

?>