<?php
    
    class DataCollectionAdapter implements Countable {
        
        private $table = null;
        private $idField = '';
        private $FieldArray = null;
        protected $filter='';
        private $ItemType='';
        private $Query='';
        public  $ordering = '';
        
        private $ItemConstants = null;
        
        protected $items = array();
       
        private $TotalQuery = '';
        
        public function __construct($itemType,$Table,$IDField,$fieldArray,$filterArray = null, $Ordering = null) {
            $this->ItemType = $itemType;
            $this->table = $Table;
            $this->idField = $IDField;
            $this->FieldArray = $fieldArray;
            $this->ordering = $Ordering;
            $qry= new QueryBuilder();
            $this->filter = $qry->BuildConditions($filterArray);
        }
        
        
        public function Load($useIdAsKey = false, $startIndex = 0, $rowCount = 0, $onlyVisibleItems = true) {
            $DB = DatabaseProvider::provide();
            $sql = $this->getSelectQuery($startIndex, $rowCount, $onlyVisibleItems);
           
            $ok = $DB->Query($sql);
            $this->items=array();
            if ($DB->RowCount() > 0) {
                if (empty($this->ItemType)) {
                    $this->items = $DB->ReadAll(($useIdAsKey?$this->idField:''),$this->ItemConstants);
                } else {
                    while ($row = $DB->ReadRow()) {
                        $itemID = $row[$this->idField];
                        $k = ($useIdAsKey?$itemID:null);
                        $this->CreateItem($k,$row, $itemID);
                    }
                }
                $this->OnLoad(true);
            } else {
                $this->OnLoad(false);
            }
            return $this->items;
        }
        protected function getSelectQuery($startIndex,$rowCount,$onlyVisibleItems) {
            if (empty($this->Query)) {
                $qry = new QueryBuilder();
                $qry->Select($this->FieldArray,$this->table);
                if ($onlyVisibleItems) {
                    $qry->Where('visible=1 '.(empty($this->filter)?'':' AND '.$this->filter));
                } else {
                    if (!empty($this->filter)) 
                        $qry->Where($this->filter);
                }
            } else {
                $qry = new QueryBuilder();
                $qry->SetQuery($this->Query);
            }
            if (!empty($this->ordering)) $qry->OrderBy($this->ordering);
            return $qry->Limit($startIndex,$rowCount)->GetQuery();
        }
        protected function OnLoad($success) {
            return $success;    
        }
        
        /*
        public function update($valuesToSet,$excludeNulls = true) {
            $DB = DatabaseProvider::provide();
            $sql = $this->getUpdateQuery($valuesToSet,$excludeNulls);
            $ok = $DB->Query($sql);
            $this->OnUpdate($ok);
            return $ok;
        }
        protected function getUpdateQuery($valuesToSet,$excludeNulls) {
            $qry = new QueryBuilder();
            return $qry->Update($this->FieldArray,$this->table,$valuesToSet,$this->idField,$excludeNulls)->Where($this->filter)->GetQuery();
        }
        protected function OnUpdate($success) {
            return $success;    
        }
        
        public function delete() {
            $DB = DatabaseProvider::provide();
            $sql = $this->getDeleteQuery();
            $ok = $DB->Query($sql);
            $this->OnDelete($ok);
            return $ok;
        }
        protected function getDeleteQuery() {
            $qry = new QueryBuilder();
            return $qry->Delete($this->table)->Where($this->filter)->GetQuery();
        }
        protected function OnDelete($success) {
            return $success;    
        }
        
        */
        
        
        
        
        
        
        
        
        
        
        public function CreateItem($key, $data, $id) {
            if (isset($this->ItemConstants) && is_array($this->ItemConstants)) {
                $data = array_merge($data,$this->ItemConstants);
            }
            $item = new $this->ItemType($id);
            $item->IsCollectionItem = true;
            $item->Load($data);
            if (empty($key)) {
                $this->items[] = $item;
            } else {
                $this->items[$key] = $item;
            }
        }
        
        public function totalCount($onlyVisibleItems = true) {
            $DB = DatabaseProvider::provide();
            $sql = '';
            if (empty($this->TotalQuery)) {
                if (empty($this->Query)) {
                    $qry = new QueryBuilder();
                    $qry->Select(array('count('.$this->idField.')'),$this->table);
                    if ($onlyVisibleItems) {
                        $qry->Where('visible=1 '.(empty($this->filter)?'':' AND '.$this->filter));
                    } else {
                        if (!empty($this->filter)) 
                        $qry->Where($this->filter);
                    }
                    $sql = $qry->GetQuery();
                } else {
                    $sql ='SELECT count(*) '.mb_substr($this->Query, mb_strpos($this->Query,' FROM '));
                }
            } else {
                $sql = $this->TotalQuery;
            }
            $ok = $DB->Scalar($sql);
            if ($ok==false) return 0;
            return intval($ok);
        }
        
        public function setTotalCountQuery($query) {
            $this->TotalQuery = $query;
        }
        
        public function getTotalCountQuery() {
            return $this->TotalQuery;
        }
        
        /*
        public function getPoses($posField = 'pos') {
            $DB = DatabaseProvider::provide();
            $sql = '';
            if (empty($this->selectQuery)) {
                $qry = new QueryBuilder();
                $qry->Select(array('max('.$posField.') as maxpos','min('.$posField.') as minpos'),$this->table);
                if ($onlyVisibleItems) {
                    $qry->Where('visible=1 '.(empty($this->filter)?'':' AND '.$this->filter));
                } else {
                    $qry->Where($this->filter);
                }
                $sql = $qry->GetQuery();
            } else {
                $sql ='SELECT max('.$posField.') as maxpos,min('.$posField.') as minpos '.mb_substr($this->selectQuery, mb_strpos($this->selectQuery,' FROM '));
            }
            $ok = $DB->Fetch($sql);
            if ($ok==false) return false;
            return array('max'=>$ok['maxpos'],'min'=>$ok['minpos']);
        }
        */
        
       
        
        public function toArray($preserveKeys = false) {
            if (isset($this->items) && is_array(reset($this->items))) {
                return $this->items;
            } else {
                $arr = array();
                if ($preserveKeys==false) {
                    foreach ($this->items as $value) {
                        $arr[] = $value->toArray();
                    }
                } else {
                    foreach ($this->items as $key=>$value) {
                        $arr[$key] = $value->toArray();
                    }
                }
                return $arr;
            }
            return false;
        }
        
        public function toJson() {
            $ret = "[";
            foreach ($this->items as $value) {
                if (is_array($value)) {
                   $ret = $ret.SerilizeToJson($value);
                } else {
                   $ret = $ret.($value->toJson());
                }
            }
            $ret = $ret.']';
            return $ret;
        }
        
        
        public function SetQuery($query) {
            $this->Query = $query;
        }
        
        public function getFilters() {
            return $this->filter;
        }

        public function count() {
            return count($this->items);
        }
        
        
        public function setConstants($ConstArray) {
            $this->ItemConstants = $ConstArray;
        }
        
        


        






















//        
//        // must override
//        protected $table = null; // ' #__table'
//        protected $idField = 'id';
//        protected $FieldArray = null; // new Array('id', 'name_#', 'descr_#','visible', 'imgsrc');
//        
//        protected $ordering = ''; // pos desc,id asc
//        
//                
//        public function __construct() {
//            parent::__construct();
//        }
//        
//        
//        public function Load($useIdAsKey = false,$startIndex=0,$rowCount=0,$onlyVisibleItems = true) {
//            $DB = DatabaseProvider::provide();
//            $sql = $this->getSelectQuery($startIndex,$rowCount,$onlyVisibleItems);
//            
//            $DB->Query($sql);
//            $this->Clear();
//            if ($DB->RowCount()>0) {
//                 while($row = $DB->ReadRow()) {
//                    $k = null;
//                    if ($useIdAsKey) {
//                        $k=$row[$this->idField];
//                    }
//                    $this->CreateItem($row,$k);
//                 }
//                 $this->OnAfterLoad(true);
//            } else {
//                 $this->OnAfterLoad(false);   
//            }
//            return $this->items;
//        }
//        
//        
//        
//        protected function OnAfterLoad($success) {
//            return $success;    
//        }
//        
//        protected function OnAfterSave($success,$method = 'insert') {
//            return $success;    
//        }
//        
//        protected function OnAfterDelete($success) {
//            return $success;    
//        }
//       
//        
//        
//        protected function getSelectQuery($startIndex,$rowCount,$onlyVisibleItems) {
//            $qry = new QueryBuilder();
//            $qry->Select($this->FieldArray,$this->table);
//            if ($onlyVisibleItems) {
//                $qry->Where('visible=1');
//            }
//            if (!empty($this->ordering)) $qry->OrderBy($this->ordering);
//            return $qry->Limit($startIndex,$rowCount)->GetQuery();
//        }
//        
//        
        
        
        
        
        
        
        
    }
    
   
//<!--Т-->
 /*
 abstract class DataCollectionAdapter extends CollectionBase {
        
        // must override
        protected $table = null; // ' #__table'
        protected $FieldArray = null; // new Array('id', 'name_#', 'descr_#','visible', 'imgsrc');
        
        protected $ordering = ''; // pos desc,id asc
        protected $bindAsObject = false;
                
        public function __construct($mBindAsObject = false) {
            $this->bindAsObject = $mBindAsObject;
        }
        
        
        public function Load($startIndex=0,$rowCount=0) {
            $DB = DatabaseProvider::provide();
            $sql = $this->getSelectQuery();
            
            if ($rowCount>0) $sql = $DB->PaginateQuery($sql,$startIndex,$rowCount);
            
            $DB->Query($sql);
            $this->Clear();
            if ($DB->RowCount()>0) {
                 while($row = $DB->ReadRow()) {
                    $this->CreateFromArray($row);
                 }
                 $this->OnAfterLoad(true);
            } else {
                 $this->OnAfterLoad(false);   
            }
            return $row;
        }
        
        
        
        protected function OnAfterLoad($success) {
            return $success;    
        }
        
       
        
        
        protected function getSelectQuery() {
            $fields = '';
            foreach($this->FieldArray as $value) {
                $key = $value;
                if (substr($key,strlen($key)-2,2)=='_#') {
                    $key =$key.' as '.substr($key,0,strlen($key)-2);     
                }
                $fields = $fields.$key.',';
            }
            $fields = rtrim($fields,',');
            $sql = "SELECT {$fields} FROM {$this->table}".(!empty($ordering)?' ORDER BY '.$ordering:'');
            return $sql;
        }
        
        
        
        
        public function bind($DataTable) {
            foreach ($DataTable as $value) {
                $this->CreateFromArray($value);
             }    
        }
        
        
        protected function CreateFromArray($arr) {
            $item = new $this->ItemType(null,$this->bindAsObject);
            $item->bind($arr);
            $this->Add($item,$arr[$item->idField]);    
        }
        
        
        
        
    }
    
      protected function getMoveQuery($direction) {
            $sql = "";
            // 'select pos from '.$_conf[table_prefix].'slides where id='.$_REQUEST[id]
            // TODO: DBQUERY in SQL build function?
            if ($this->BindMode == DataBindMode::Item) {
                if ($this->IsChild==true) {
                    
                    
                } else {
                    
                    
                }
                
            } elseif ($this->BindMode == DataBindMode::Collection) {
                // TODO: not supported
            }
            
            if ($direction=="up") {
                $row=mysql_fetch_array(mysql_query('select pos from '.$_conf[table_prefix].'slides where id='.$_REQUEST[id]));
				$rowm=mysql_fetch_array(mysql_query('SELECT MIN(pos) pos FROM '.$_conf[table_prefix].'slides'));
				if($rowm[pos]!=$row[pos]){
					mysql_query('update '.$_conf[table_prefix].'slides set pos=pos+1 where pos='.($row[pos]-1));
					mysql_query('update '.$_conf[table_prefix].'slides set pos=pos-1 where id='.$_REQUEST[id]);
				}

            } elseif ($direction=="down") {
                $row=mysql_fetch_array(mysql_query('select pos from '.$_conf[table_prefix].'slides where id='.$_REQUEST[id]));
				$rowm=mysql_fetch_array(mysql_query('SELECT MAX(pos) pos FROM '.$_conf[table_prefix].'slides'));
				if($rowm[pos]!=$row[pos]){
					mysql_query('update '.$_conf[table_prefix].'slides set pos=pos-1 where pos='.($row[pos]+1));
					mysql_query('update '.$_conf[table_prefix].'slides set pos=pos+1 where id='.$_REQUEST[id]);
				}

            }
            
            
            
                $sql = "DELETE FROM {$this->table} WHERE {$this->idField} = '{$this->id}'";
            
            return $sql;
        }
    
        protected function getVisibleQuery() {
            $sql = "";
            if ($this->BindMode == DataBindMode::Item) {
                $vis = $this->visible?1:0;
                $sql = "UPDATE {$this->table} set visible={$vis} where {$this->idField}='{$this->id}'";
            } elseif ($this->BindMode == DataBindMode::Collection) {
                // TODO: delete collection query
            }
            return $sql;
        }
    require_once ROOTDIR."core/database/_conf.php";
            $this->Items = $_conf;
    */
    
?>