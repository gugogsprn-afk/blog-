<?php

    class Banner extends TableEditableItem {

        // must override
        
        protected $table = '#__banners';
        protected $FieldArray = Array('id', 'name', 'content','content_lg1','content_lg2', 'validdate_$', 'context', 'place', 'priority');
        protected $CollectionFieldArray = Array('id', 'name', 'TIMESTAMPDIFF(DAY,CURDATE(),validdate) as daysleft', 'validdate_$', 'context','place','priority');
        protected $ValidationFields = Array('name'=>'!null','validdate'=>'date');
        
        protected function OnLoad($success) {
            parent::OnLoad($success);
            if ($success) {
                if ($this->IsCollectionItem) {
                    
                } else {
                    $context = intval($this->getProp('context'));
                    $this->setProp('context_'.$context,'selected="selected"');
                    $this->setProp('place_'.$this->getProp('place'),'selected="selected"');
                }
                $iid = intval($this->getProp('id'));
                
                if ($iid<=0)
                    throw new EPageError(EPageError::CUSTOM_ERROR,'Banner not found');
                $this->ID = $iid;
             
            }
        }
        
        public function getJsonCollection($pageSize,$startIndex,$pageNum,$orderby) {
            if ($pageNum<=0)                
                throw new EPageError(EPageError::CUSTOM_ERROR,"page number is wrong");
            $ItemCollection = $this->Collection();
            $ItemCollection->ordering =empty($orderby)?'id':$orderby; // TODO: ordering in grid
            
            $where = "";
            if (isset($this->Props) && is_array($this->Props) && count($this->Props)>0) {
                $qry = new QueryBuilder();
                $where =" WHERE ".$qry->BuildConditions($this->Props);
            }
            $DB = DatabaseProvider::provide();
            $ItemCollection->Load(false,$startIndex,$pageSize,false);
            $totalRows = $ItemCollection->totalCount(false);
            $arr = array( "page" => $pageNum,"records" => $totalRows, "rows" => $ItemCollection->toArray());
            return SerilizeToJson($arr);
        }
        
        
        public function InsertFromOv($itemProps,$urlItems) {
            $itemProps['priority'] = intval($itemProps['priority']);
            $itemProps['context'] = intval($itemProps['context']);
            $this->bindData($itemProps);
            $this->Insert();
            if (empty($this->ID))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Banner creation error');
            
            $DB = DatabaseProvider::provide();
            
            $ok = $DB->Query("DELETE FROM #__urlmapper WHERE item_id=".$this->ID." AND itemtype = 'banner'");
            if ($ok===false)
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating pages of banner');
            
            if (count($urlItems)>0) {
                foreach ($urlItems as $key => $value) {
                    $urlID =intval($value['id']);
                    $children = intval($value['children']);
                    if (empty($urlID))
                        continue;
                    $DB->Query("INSERT INTO #__urlmapper SET
                                    item_id=".$this->ID.",
                                    url_id = ".$urlID.",
                                    itemtype = 'banner',    
                                    children = ".$children);
                }
            }
            return true;
        }
        
        public function UpdateFromOv($itemProps,$urlItems) {
            $itemProps['priority'] = intval($itemProps['priority']);
            $itemProps['context'] = intval($itemProps['context']);
            $this->Props = $itemProps;
            $this->Update();
            if (empty($this->ID))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error updating banner');
            $DB = DatabaseProvider::provide();
            
            $ok = $DB->Query("DELETE FROM #__urlmapper WHERE item_id=".$this->ID." AND itemtype = 'banner'");
            if ($ok===false)
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error creating pages of banner');
            
            if (count($urlItems)>0) {
                foreach ($urlItems as $key => $value) {
                    $urlID =intval($value['id']);
                    $children = intval($value['children']);
                    if (empty($urlID))
                        continue;
                    $DB->Query("INSERT INTO #__urlmapper SET
                                    item_id=".$this->ID.",
                                    url_id = ".$urlID.",
                                    itemtype = 'banner',     
                                    children = ".$children);
                }
            }
            return true;
        }
        
        public function Remove() {
            $ok = parent::Remove();
            $DB = DatabaseProvider::provide();
            $DB->Query("DELETE FROM #__urlmapper WHERE item_id=".$this->ID." AND itemtype = 'banner'");
            return $ok;
        }
        
        
        public function getPages() {
            $DB= DatabaseProvider::provide();
            $DB->Query("SELECT tb.item_id,tb.url_id,tb.children,tu.displaytext,tu.url 
                            FROM #__urlmapper tb 
                            INNER JOIN #__urlcache tu ON tu.id = tb.url_id
                            WHERE tb.itemtype = 'banner' AND tb.item_id=".$this->ID);
            $items = array();
            if ($DB->RowCount()>0) {
                while ($row = $DB->ReadRow()) {
                    $row['children'] = (intval($row['children'])>0?'checked="checked"':'');
                    $items[] = $row;
                } 
            }
            return $items;
        }
        
        
        
        
        
         /*
        public function getJsonCollection($pageSize,$startIndex,$pageNum,$orderby) {
            $ItemCollection = $this->Collection();
            $where = '';
            if (isset($this->Props) && is_array($this->Props)) {
                $qry = new QueryBuilder();
                $where =' WHERE '.$qry->BuildConditions($this->Props);
            }
            $DB = DatabaseProvider::provide();
            $ItemCollection->ordering = $orderby;
            $ItemCollection->Load(false,$startIndex,$pageSize,false);
            $totalRows = $ItemCollection->totalCount(false);
            $arr = array("records" => $totalRows, "rows" => $ItemCollection->toArray());
            return SerilizeToJson($arr);
       }
    
    
       
        public function Collection($AsArray = false) {
            $cls = get_class ($this);
            if ($AsArray) $cls = '';
            $fields = $this->CollectionFieldArray==null?$this->FieldArray:$this->CollectionFieldArray;
            $collection = new DataCollectionAdapter($cls,$this->table,$this->idField,$fields,$this->Props);
            
            $qry = new QueryBuilder();
            $sql = $qry->Select($fields, '#__members')
                        ->Where($collection->getFilters())
                        ->GetQuery();
            
            $collection->SetQuery($sql);
            
            return $collection;
        }
       
        
        public static function BuildParents() {
            $sql = "SELECT id,pid,parent_id,componenttype,hardtype,alias,url,urlkey,urltop,urllevel
                    FROM #__urlcache WHERE id>99 ORDER BY componenttype,parent_id";
            $DB= DatabaseProvider::provide();
            $DB->Query($sql);
            if ($DB->RowCount()<=0) {
                return 'urls not found';
            }
            set_time_limit(0);

            $rows = $DB->ReadAll();

            foreach ($rows as $row) {
                $url = new UrlCache($row['parent_id'], $row['hardtype']);
                $url->Bind($row);
                $url->Update();
            } 
            return 'Building done';
            
        }
        */

    }


?>
