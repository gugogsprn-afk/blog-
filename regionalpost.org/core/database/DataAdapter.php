<?php

    abstract class DataAdapter extends EntityBase {
        
        // must override
        protected $table = null; // ' #__table'
        protected $idField = 'id';
        protected $FieldArray = null; // new Array('id', 'name_#', 'descr_#','visible', 'imgsrc');
        protected $CollectionFieldArray = null;
        protected $ValidationFields = array();
        
        public $thowExceptions = true;
        
        protected $trustFields = array();


        public $ID = null;
        
        public function __construct($id) {
            parent::__construct();
            $this->ID = $id;
        }


        public function Load($data = null) {
            if (!$this->BeforeLoad()) return null;
            if (empty($this->ID)) {
                throw new Exception('ERR_RECNOTFOUND');
                return false;
            }
            if (is_array($data)) {
                $this->bind($data);
                $this->OnLoad(true);
                return $data;
            }
            $DB = DatabaseProvider::provide();
            $sql = $this->getSelectQuery();
            $row = $DB->Fetch($sql);
            if ($row == false) {
                $this->OnLoad(false);
                return false;
            }
            $this->bind($row);
            $this->OnLoad(true);
            return $row;
        }
        
        public function LoadFromFields($params) {
            if (!$this->BeforeLoad()) return null;
            if (!(is_array($params) && count($params)>0))
                throw new Exception ('ERR_EMPTYBINDING');
            
            $qry = new QueryBuilder();
            $where = $qry->BuildConditions($params);
            $sql = $qry->Select($this->FieldArray, $this->table)->Where($where)->GetQuery();
            $DB = DatabaseProvider::provide();
            $row = $DB->Fetch($sql);
            if ($row == false) {
                $this->OnLoad(false);
                return FALSE;
            }
            $this->bind($row);
            $this->ID = $this->getProp($this->idField);
            $this->OnLoad(true);
            return $row;
        }
        
        
        
        protected function getSelectQuery() {
            $qry = new QueryBuilder();
            
            $sID = DatabaseProvider::provide()->EscapeValue($this->ID);
            return $qry->Select($this->FieldArray,$this->table)->Where($this->idField.'='.$sID)->GetQuery();
        }
        
        public function Insert() {
           if (!$this->BeforeInsert()) return false;
           
           $invalidFields = $this->ValidateFields();
           if (count($invalidFields)>0)               
               throw new EInvalidInputError($invalidFields);

           $DB = DatabaseProvider::provide();
           $sql = $this->getInsertQuery(); 
           $ok = $DB->Query($sql);
           if (!$ok) {
                $this->OnInsert(false);
                return false; 
           }
           $this->ID = $DB->LastID();
           $this->OnInsert(true);
           return true;
        }
        protected function getInsertQuery() {
            $qry = new QueryBuilder();
            return $qry->Insert($this->FieldArray,$this->table,$this->Props,$this->idField,$this->trustFields)->GetQuery();
        }
        
        public function Update($excludeNulls = true) {
            if (!$this->BeforeUpdate()) return false;
            if (empty($this->ID)) {
                throw new Exception('ERR_RECUPDATE');
                return false;
            }
            $invalidFields = $this->ValidateFields();
            if (count($invalidFields)>0)               
                throw new EInvalidInputError($invalidFields);
           
            $sql = $this->getUpdateQuery($excludeNulls);
            $DB = DatabaseProvider::provide();
            $ok = $DB->Query($sql);
            $this->OnUpdate($ok);
            return $ok;
        }
        protected function getUpdateQuery($excludeNulls) {
            $qry = new QueryBuilder();
            $sID = DatabaseProvider::provide()->EscapeValue($this->ID);
            return $qry->Update($this->FieldArray,$this->table,$this->Props,$this->idField,$excludeNulls,$this->trustFields)->Where($this->idField.'='.$sID)->GetQuery();
        }
        
        public function Delete() {
            if (!$this->BeforeDelete()) return false;
            if (empty($this->ID)) {
                throw new Exception('ERR_DELETEEMPTYID');
                return false;
            }
            $DB = DatabaseProvider::provide();
            $sql = $this->getDeleteQuery();
            $ok = $DB->Query($sql);
            $this->OnDelete($ok);
            return $ok;
        }
        protected function getDeleteQuery() {
            $qry = new QueryBuilder();
            $sID = DatabaseProvider::provide()->EscapeValue($this->ID);
            return $qry->Delete($this->table)->Where($this->idField.'='.$sID)->GetQuery();
        }
        
        protected function BeforeLoad() {
            return true;    
        }
        protected function OnLoad($success) {
            if ($success===false && $this->thowExceptions) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_RECNOTFOUND');
            }
            return $success;    
        }
        
        protected function BeforeUpdate() {
            return true;    
        }
        protected function OnUpdate($success) {
            if ($success===false && $this->thowExceptions) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_RECUPDATE');
            }
            return $success;    
        }
        
        protected function BeforeInsert() {
            return true;    
        }
        protected function OnInsert($success) {
            if ($success===false && $this->thowExceptions) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_RECINSERT');
            }
            return $success;    
        }
        
        protected function BeforeDelete() {
            return true;    
        }
        protected function OnDelete($success) {
            if ($success===false && $this->thowExceptions) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'ERR_RECDELETE');
            }
            return $success;    
        }
        
        public function UpdateOrInsert($excludeNulls = true) {
            $ret = false;
            if (empty($this->ID)) {
                $ret = $this->Insert();
            } else {
                $ret = $this->Update($excludeNulls);
            }
            return $ret;
        }
        
        
        
        protected function bind($data) {
            $this->Props = $data;
        }
        
        
        public function bindData($data) {
            if (isset($data) && is_array($data)) {
                foreach ($data as $key => $value) {
                    $this->setProp($key, $value);
                }
            }
        }
        
        
        /**
         * 
         * @return DataCollectionAdapter
         */
        public function Collection($AsArray = false) {
            $cls = get_class ($this);
            if ($AsArray) $cls = '';
            $fields = $this->CollectionFieldArray==null?$this->FieldArray:$this->CollectionFieldArray;
            return new DataCollectionAdapter($cls,$this->table,$this->idField,$fields,$this->Props);
        }
        
        public function LoadCollection($pageSize,$startIndex,$pageNum,$orderby) {
            if ($pageNum<=0)                
                throw new EPageError(EPageError::CUSTOM_ERROR,"ERR_WRONGPAGE");
            
            $ItemCollection = $this->Collection(true);
            
            if (!empty($orderby)) {
                $ItemCollection->ordering  = $orderby;
            }
            
            $where = '';
            if (isset($this->Props) && is_array($this->Props) && count($this->Props)>0) {
                $qry = new QueryBuilder();
                $where =' WHERE '.$qry->BuildConditions($this->Props);
            }
            
            $ItemCollection->Load(false,$startIndex,$pageSize,false);
            $totalRows = $ItemCollection->totalCount(false);
            $arr = array( "page" => $pageNum,"records" => $totalRows, "rows" => $ItemCollection->toArray());
            
            return $arr;
        }
        
        
        public function toJson() {
            return SerilizeToJson($this->Props);
        }
        
        protected function ValidateFields() {
            $invalidFields = array();
            if (!empty($this->ValidationFields) && is_array($this->ValidationFields)) {
                foreach ($this->ValidationFields as $field=>$condition) {
                    if (!$this->propExists($field)) continue;
                    $ok = false;
                    if ($condition==='date') {
                        $dte = ParseDate($this->getProp($field));
                        if ($dte===null) {
                            $ok = false;
                        } else {
                            $ok = true;
                        }
                    } elseif ($condition==='datetime') {
                        $ok = isValidDateTime($this->getProp($field));
                    } else {
                        $ok = ValueComparer::compare($field, $this->getProp($field), $condition);
                    }
                    if ($ok==false)
                        $invalidFields[] = strtolower (get_class($this)).'-'.$field;
                }
            }
            return $invalidFields;
        }




   }

    
?>