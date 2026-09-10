<?php
    
    class TableEditableItem extends DataAdapter {
        
        protected function OnLoad($success) {
            if ($success) {
                $visible = _VARINT($this->getProp('visible'))==1?true:false;
                $this->setProp('notvisible', ($visible?'0':'1'));
                $this->setProp('visiblemark', ($visible?'':'n'));
                $this->setProp('showup', (_VARINT($this->getProp('pos'))>_VARINT($this->getProp('minpos'))?'1':'0'));
                $this->setProp('showdown',(_VARINT($this->getProp('pos'))<_VARINT($this->getProp('maxpos'))?'1':'0'));
            }
        }
        
        public function getJsonCollection($pageSize,$startIndex,$pageNum,$orderby) {
            if ($pageNum<=0)                
                throw new EPageError(EPageError::CUSTOM_ERROR,"page number is wrong");
            $ItemCollection = $this->Collection();
            $ItemCollection->ordering =empty($orderby)?'pos':$orderby; // TODO: ordering in grid
            
            $where = '';
            if (isset($this->Props) && is_array($this->Props) && count($this->Props)>0) {
                $qry = new QueryBuilder();
                $where =' WHERE '.$qry->BuildConditions($this->Props);
            }
            $DB = DatabaseProvider::provide();
            $poses = $DB->Fetch('SELECT max(pos) as maxpos,min(pos) as minpos FROM '.$this->table.$where);
            if ($poses==false) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'failed find positions in collection');
            $ItemCollection->setConstants($poses);
            $ItemCollection->Load(false,$startIndex,$pageSize,false);
            $totalRows = $ItemCollection->totalCount(false);
            $arr = array( "page" => $pageNum,"records" => $totalRows, "rows" => $ItemCollection->toArray());
            return SerilizeToJson($arr);
        }
        
            
        public function UpdateFrom($itemProps,$excludeNulls = true) {
            $ok = false;
            if (isset($itemProps) && is_array($itemProps)) {
                foreach ($itemProps as $key => $value) {
                    $this->setProp($key, $value);
                }
                $ok = $this->Update($excludeNulls);
            }
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            return '"result" : "ok"';
        }
        
        public function InsertFrom($itemProps,$filterList=null) {
            $ok = false;
            if (empty($itemProps['pos'])) {
                $where = '';
                if (isset($filterList) && is_array($filterList)) {
                    $qry = new QueryBuilder();
                    $where =$qry->BuildConditions($filterList);
                }
                $DB = DatabaseProvider::provide();
                $maxpos = $DB->Scalar('SELECT max(pos) as maxpos FROM '.$this->table.(empty($where)?'':' WHERE '.$where));
                
                $itemProps['pos'] = intval($maxpos)+1;
            }
            if (isset($itemProps) && is_array($itemProps)) {
                foreach ($itemProps as $key => $value) {
                    $this->setProp($key, $value);
                }
                $ok = $this->Insert();
            }
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            return '"result" : "ok"';
        }
        
        
        
        public function Move($direction,$filterList=null) {
            $res = false;
            $where = '';
            if (isset($filterList) && is_array($filterList)) {
                $qry = new QueryBuilder();
                $where =$qry->BuildConditions($filterList);
            }
            
            $DB = DatabaseProvider::provide();
            $poses = $DB->Fetch('SELECT max(pos) as maxpos,min(pos) as minpos FROM '.$this->table.(empty($where)?'':' WHERE '.$where));
            if ($poses==false) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'failed find positions in collection');
            
            $curPos = _VARINT($this->getProp('pos'));
            if ($direction==='toup') {
                $minPos = _VARINT($poses['minpos']);
                if ($curPos>$minPos) {
                    $ok = $DB->Query('UPDATE '.$this->table.' SET pos=pos+1 WHERE pos='.($curPos-1).(empty($where)?'':' AND '.$where));
                    if ($ok) {
                        $res = $DB->Query('UPDATE '.$this->table.' SET pos=pos-1 WHERE id='.$this->ID);
                    }
                }
            } elseif ($direction==='todown') {
                $maxPos = _VARINT($poses['maxpos']);
                if ($curPos<$maxPos) {
                    $ok = $DB->Query('UPDATE '.$this->table.' SET pos=pos-1 WHERE pos='.($curPos+1).(empty($where)?'':' AND '.$where));
                    if ($ok) {
                        $res = $DB->Query('UPDATE '.$this->table.' SET pos=pos+1 WHERE id='.$this->ID);
                    }
                }
            } 
            if ($res == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            return '"result" : "ok"';
        }
        
        public function ChangeVisible($value) {
            $this->setProp('visible', $value);
            $ok = $this->Update();
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            return '"result" : "ok"';
        }
        
        public function Remove() {
            $ok = $this->Delete();
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            return '"result" : "ok"';
        }
        
        
        

    }

?>
