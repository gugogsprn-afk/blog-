<?php

    class QueryBuilder {
    
        private $sql='';
        
        public function __construct() {
        }
        
        public function SetQuery($query) {
            $this->sql=$query; 
            return $this;   
        }
        
        public function GetQuery($clean = false) {
            $bld = $this->sql;
            if ($clean) $this->sql='';
            return $bld;    
        }
        
        public function Reset() {
            $this->sql='';
        }
        
        public function Select($FieldArray,$tableName,$distinct=false) {
            $fields = '';
            foreach($FieldArray as $value) {
                $key = $value;
                $suf = substr($key,strlen($key)-2,2);
                if ($suf=='_#') {
                    $fldName= substr($key,0,strlen($key)-2);
                    $pos = strrpos($fldName, ".");
                    if ($pos!==false) {
                        $fldName = substr($fldName,$pos+1);
                    }
                    $key =$key.' as '.$fldName;    
                } else if ($suf=='_&') {
                    $fldName= substr($key,0,strlen($key)-2);
                    $pos = strrpos($fldName, ".");
                    if ($pos!==false) {
                        $fldName = substr($fldName,$pos+1);
                    }
                    $key =$key.' as '.$fldName;     
                } else if ($suf=='_~') {
                    $fldName= substr($key,0,strlen($key)-2);
                    $key = $fldName;
                    $pos = strrpos($fldName, ".");
                    if ($pos!==false) {
                        $fldName = substr($fldName,$pos+1);
                    }
                    $key ="DATE_FORMAT(".$key.",'%d.%m.%Y %H:%i:%s') as ".$fldName;  
                } else if ($suf=='_$') {
                    $fldName= substr($key,0,strlen($key)-2);
                    $key = $fldName;
                    $pos = strrpos($fldName, ".");
                    if ($pos!==false) {
                        $fldName = substr($fldName,$pos+1);
                    }
                    $key ="DATE_FORMAT(".$key.",'%d.%m.%Y') as ".$fldName;  
                }
                $fields = $fields.$key.',';
            }
            $this->sql = 'SELECT '.($distinct===true?'DISTINCT ':'').rtrim($fields,',').' FROM '.$tableName;
            return $this;
        }
        
        public function Insert($FieldArray,$tableName,$values,$IdFieldToEscape ='',$notEscapeAndQuoteFields = array()) {
            $this->sql  = 'INSERT INTO '.$tableName.' set ';
            $KeyValue = "";
            foreach($FieldArray as $key) {
                $property = $key;
                $suf = substr($property,strlen($property)-2,2);
                $tpe = '';
                if ($suf =='_#') {
                    $property = substr($property,0,strlen($property)-2);
                } else if ($suf =='_&') {
                    $property = substr($property,0,strlen($property)-2);    
                } else if ($suf =='_~') {
                    $property = substr($property,0,strlen($property)-2);
                    $tpe = 'long';
                    $key = $property;
                } else if ($suf =='_$') {
                    $property = substr($property,0,strlen($property)-2);
                    $tpe = 'short';
                    $key = $property;
                }
                
                if (!array_key_exists($property,$values)) continue;
                if ($property==$IdFieldToEscape) continue;
                $value = trim($values[$property]);
                
                if (count($notEscapeAndQuoteFields)>0 && in_array($key, $notEscapeAndQuoteFields)) {
                    $tpe = '';
                } else {
                    $value = DatabaseProvider::provide()->EscapeValue($value,true);
                }

                if ($tpe == 'short') {
                    $KeyValue .= $key." = STR_TO_DATE(".$value.",'%d.%m.%Y'),";
                } else if ($tpe == 'long') {
                    $KeyValue .= $key." = STR_TO_DATE(".$value.",'%d.%m.%Y %H:%i:%s'),";
                } else {
                    $KeyValue .= $key.' = '.$value.',';
                }
            }
            $this->sql =$this->sql.rtrim($KeyValue,",");
            return $this;    
        }
        
        public function Update($FieldArray,$tableName,$values,$IdFieldToEscape ='',$excludeNulls = false,$notEscapeAndQuoteFields = array()) {
            $this->sql  = 'UPDATE '.$tableName.' set ';
            $KeyValue = "";
            foreach($FieldArray as $key) {
                $property = $key;
                
                $suf = substr($property,strlen($property)-2,2);
                $tpe = '';
                if ($suf =='_#') {
                    $property = substr($property,0,strlen($property)-2);
                } else if ($suf =='_&') {
                    $property = substr($property,0,strlen($property)-2);
                } else if ($suf =='_~') {
                    $property = substr($property,0,strlen($property)-2);
                    $tpe = 'long';
                    $key = $property;
                } else if ($suf =='_$') {
                    $property = substr($property,0,strlen($property)-2);
                    $tpe = 'short';
                    $key = $property;
                }
                
                if (!array_key_exists($property,$values)) continue;
                if ($property==$IdFieldToEscape) continue;
                
                if ($excludeNulls && $values[$property]===null) continue;
                
                $value = trim($values[$property]);
                
                if (count($notEscapeAndQuoteFields)>0 && in_array($key, $notEscapeAndQuoteFields)) {
                    $tpe = '';
                } else {
                    $value = DatabaseProvider::provide()->EscapeValue($value,true);
                }

                if ($tpe == 'short') {
                    $KeyValue .= $key." = STR_TO_DATE(".$value.",'%d.%m.%Y'),";
                } else if ($tpe == 'long') {
                    $KeyValue .= $key." = STR_TO_DATE(".$value.",'%d.%m.%Y %H:%i:%s'),";
                } else {
                    $KeyValue .= $key.' = '.$value.',';
                }
            }
            $this->sql =$this->sql.rtrim($KeyValue,",");
            return $this;
        }
        
        public function Delete($tableName) {
            $this->sql = 'DELETE FROM '.$tableName;
            return $this;    
        }
        
        
        
        public function Where($where,$append = 'WHERE') {
            $this->sql = $this->sql.' '.$append.' '.$where;
            return $this;
        }
        
        public function Join($table,$on,$mode = 'LEFT') {
            $this->sql = $this->sql.' '.$mode.' JOIN '.$table.' ON '.$on;
            return $this;    
        }
        
        public function OrderBy($order) {
            $this->sql = $this->sql.' ORDER BY '.$order;
            return $this;    
        }
        
        public function GroupBy($groupings) {
            $this->sql = $this->sql.' GROUP BY '.$groupings;
            return $this;    
        }
        
        public function Limit($start,$length) {
            $start = intval($start);
            $length = intval($length);
            if ( $start==0 && $length==0) return $this;
            $this->sql = $this->sql.' LIMIT '.$start.($length==0?'':','.$length);
            return $this;    
        }
        
        public function AppendQuery($query) {
            $this->sql = $this->sql.$query;
            return $this;    
        }
        
        public function BuildConditions($Arr,$Operator = 'AND',$ColPrefix='') {
            $filter = '';
            if (is_array($Arr) && count($Arr)>0) {
                $filters = array();
                foreach($Arr as $key=>$value) {
                    if (is_array($value)) {
                        $cond = ' '.$value['cond'].' ';
                        $value = $value['value'];
                        if (empty($value['noescape']))
                            $value = DatabaseProvider::provide()->EscapeValue($value);                           
                        $filters[] = $ColPrefix.$key.$cond.$value; 
                    } else {
                        $value = DatabaseProvider::provide()->EscapeValue($value);                           
                        $filters[] = $ColPrefix.$key.' = '.$value; 
                    }
                }
                $filter = implode(' '.$Operator.' ', $filters);
            }
            return $filter;
        }
        
        
    }



?>