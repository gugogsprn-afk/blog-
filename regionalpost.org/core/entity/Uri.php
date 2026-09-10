<?php

class Uri extends EntityBase {
    
    private $itemTypes = array('root','helper','category','item','file','search');
    
    public function __construct($itemID,$item_Type) {
        parent::__construct();
        $this->setProp('parent_id',_VARINT($itemID));
        $this->setProp('itemtype', $item_Type);
    }

    public function Bind($props) {
        foreach ($props as $key => $value) {
            $this->Props[$key] = $value;
        }
        $robots = _VARINT($this->getProp('meta_robots'));
        $this->setProp('meta_robots', ($robots>0?true:false));
    }

    public function Load($throw = true) {
        if (!in_array($this->getProp('itemtype'), $this->itemTypes))
            throw new EPageError(EPageError::PAGE_NOT_FOUND);
        $DB = DatabaseProvider::provide();
        $row = $DB->Fetch("SELECT id, pid, parent_id, componenttype, voider, itemtype, alias, displaytext, url, urlkey, visible, urltop, urllevel,meta_title_# as meta_title,meta_keys_# as meta_keys, meta_descr_# as  meta_descr,meta_robots,cache_allow,DATE_FORMAT(date_modified,'%a, %d %b %Y %T') as date_modified,UNIX_TIMESTAMP(date_modified) as date_modifiedtime
                            FROM #__urlcache WHERE parent_id=".  _VARINT($this->getProp('parent_id'))." AND itemtype='".$this->getProp('itemtype')."'");
        if ($DB->RowCount()<=0 && $throw===true) 
            throw new EPageError(EPageError::PAGE_NOT_FOUND);
        $this->Bind($row);
        return $row;
    }
    
    
    
    /**
     * 
     * @param type $url
     * @throws EPageError
     * @var Uri
     */
    public static function FindUrl($url) {
        $DB = DatabaseProvider::provide();
        $url = $DB->EscapeValue($url);
        $row = $DB->Fetch("SELECT id, pid, parent_id, componenttype, voider, itemtype, alias, displaytext, url, urlkey, visible, urltop, urllevel,meta_title_# as meta_title,meta_keys_# as meta_keys, meta_descr_# as  meta_descr,meta_robots,cache_allow,DATE_FORMAT(date_modified,'%a, %d %b %Y %T') as date_modified,UNIX_TIMESTAMP(date_modified) as date_modifiedtime FROM #__urlcache WHERE urlkey = CRC32(".$url.") AND url=".$url);
        if ($row===false) 
            throw new EPageError(EPageError::PAGE_NOT_FOUND,$url.' url not found');
        if ($DB->RowCount()<=0) 
            throw new EPageError(EPageError::PAGE_NOT_FOUND,$url.' url not found');
        $found = new Uri($row['parent_id'],$row['itemtype']);
        $found->Bind($row);
        return $found;
    }
    
    public static function Exists($url) {
        $DB = DatabaseProvider::provide();
        $exists = $DB->Scalar("SELECT COUNT(*) FROM #__urlcache WHERE urlkey = CRC32('".$url."') AND url='".$url."'");
        if (intval($exists)>0) return true;
        return false;
    }
    
    
    /**
     * 
     * @param type $id
     * @return \Uri
     * @throws EPageError
     */
    public static function FindById($id) {
        $DB = DatabaseProvider::provide();
        $url = $DB->EscapeValue($url);
        $row = $DB->Fetch("SELECT id, pid, parent_id, componenttype, voider, itemtype, alias, displaytext, url, urlkey, visible, urltop, urllevel,meta_title_# as meta_title,meta_keys_# as meta_keys, meta_descr_# as  meta_descr,meta_robots,cache_allow,DATE_FORMAT(date_modified,'%a, %d %b %Y %T') as date_modified,UNIX_TIMESTAMP(date_modified) as date_modifiedtime FROM #__urlcache WHERE id = ". _VARINT($id));
        if ($row===false || $DB->RowCount()<=0) {
            return null;
        }
        $found = new Uri($row['parent_id'],$row['itemtype']);
        $found->Bind($row);
        return $found;
    }
   
    
    
    

}
?>