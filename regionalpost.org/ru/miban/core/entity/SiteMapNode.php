<?php

    class SiteMapNode  extends TableEditableItem {

        // must override
        protected $table = ' #__sitemap';
        protected $idField = 'id';
        protected $FieldArray = Array('id', 'parent_id', 'url_parent_id', 'url_hard_type', 'totop', 'nodelevel','name');
        protected $CollectionFieldArray = Array('tc.id', 'tc.name', 'tc.parent_id', 'tc.name', 'tc.totop', 'tc.nodelevel', 'tc.pos', 'tc.visible','count(DISTINCT td.id) as child_count');
        
        
        
        public function __construct($id) {
            parent::__construct();
        }
        
        public function getFromUrl($Parent_id,$itemtype) {
            $DB = DatabaseProvider::provide();
            $Parent_id = intval($Parent_id);
            $itemtype = $DB->EscapeValue($itemtype);
            $qry = new QueryBuilder();
            $qry->Select($this->FieldArray, $this->table)->Where('url_parent = '.$Parent_id." AND url_type=".$itemtype);
            
            $row = $DB->Fetch($qry->GetQuery());
            if ($row===false || $DB->RowCount()<=0) 
                return null;
            $MapNode = new SiteMapNode(null);
            $MapNode->Load($row);
            $MapNode->ID =intval($row['id']);
            return $MapNode;
        }
        
        protected function OnLoad($success) {
            parent::OnLoad($success);
            if ($success) {
                if ($this->IsCollectionItem) {
                    $this->setProp('Children', (_VARINT($this->getProp('child_count'))>0?true:false));
                } else {
                    $path = trim($this->getProp('totop'), ',');
                    $fullPath = trim($this->getProp('totop').$this->ID,',');
                    $this->setProp('fullpath', $fullPath);
                    $this->setProp('path', $path);
                }
            }
        }
        
        public function getJsonCollection() {
            $ItemCollection = $this->Collection();
            $ItemCollection->Load();
            /*
            $arr = array(); //$ItemCollection->toArray();
            if (count($arr)<=0) {
                $arr[] = array('id'=>0,'parent_id'=>0,'name'=>'Catalog','totop'=>'','nodelevel'=>'-1','pos'=>0,'visible'=>'1','visiblemark'=>'','showup'=>'0','showdown'=>'0');
            }*/
            return SerilizeToJson($ItemCollection->toArray());
        }
        
        public function Collection($AsArray = false) {
            $cls = get_class ($this);
            if ($AsArray) $cls = '';
            $collection = new DataCollectionAdapter($cls,$this->table,$this->idField,$this->CollectionFieldArray,array('tc.parent_id'=> _VARINT($this->getProp('parent_id'))));
            $qry = new QueryBuilder();
            $sql = $qry->Select($this->CollectionFieldArray, '#__sitemap tc')->Join('#__sitemap td', 'td.parent_id =tc.id')->Where($collection->getFilters())->GroupBy('tc.id')->GetQuery();
            $collection->SetQuery($sql);
            return $collection;
        }
        
        public function InsertFromOv($itemProps) {
            $itemProps['url_parent'] = $parent_id;
            $itemProps['url_type'] = $itemtype;
            $itemProps['componenttype'] = $compType;
            
            $parent = $this->findParentAndName($itemProps);
            
            $pid = _VARINT($itemProps['url_parent']);
             if ($pid>0) {
                 $cat = new SiteMapNode($pid);
                 $cat->Load();
                 $itemProps['nodelevel'] = _VARINT($cat->getProp('nodelevel'))+1;
                 $itemProps['totop'] = $cat->getProp('totop').$cat->ID.',';
             } else {
                 $itemProps['nodelevel'] = 0;
                 $itemProps['totop'] = ',';
             }
             $filterList = array('parent_id'=>$pid);
             $ok = parent::InsertFrom($itemProps, $filterList);
             return $ok;
        }
        
        
        public function UpdateFromOv($itemProps) {
             $pid = _VARINT($itemProps['parent_id']);

             $cat = new SiteMapNode($this->ID);
             $cat->Load();
             $oldLevel = _VARINT($cat->getProp('nodelevel'));
             $oldTotop = $cat->getProp('totop');
             $oldParent = _VARINT($cat->getProp('parent_id'));
             unset($cat);
             if ($pid>0) {
                 $cat = new SiteMapNode($pid);
                 $cat->Load();
                 $itemProps['nodelevel'] = _VARINT($cat->getProp('nodelevel'))+1;
                 $itemProps['totop'] = $cat->getProp('totop').$cat->ID.',';
             } else {
                 $itemProps['nodelevel'] = 1;
                 $itemProps['totop'] = ',';
             }
             
             $ok = parent::UpdateFrom($itemProps);
             if ($pid!=$oldParent) {
                $qry = "update ".$this->table." set
                               totop = REPLACE(totop,'".$oldTotop."','".$itemProps['totop']."'),
                               nodelevel = (nodelevel+(".$itemProps['nodelevel']."-".$oldLevel."))
                        where parent_id=".$this->ID." OR totop LIKE CONCAT('%,',".$this->ID.",',%')";
                $db = DatabaseProvider::provide();
                $db->Query($qry);
             }
             return $ok;
        }
        
        
        
        public function Remove() {
            $ok = parent::Remove();
            return $ok;
        }
        
        
        public function findParentAndName() {
        
            $url_parent =intval($this->getProp('url_parent'));
            $url_parentType = $this->getProp('url_parenttype');
            $urlParentObject = new UrlCache($url_parent,$url_parentType);
            $urlParentObject->Load();
            $url_level = _VARINT($urlParentObject->getProp('nodelevel'))+1;
            $url_level_totop = $urlParentObject->getProp('totop').$url_parent.'|'.$url_parentType.',';
            $url_parentType = $DB->EscapeValue($url_parentType,false);
            $url_level_totop = $DB->EscapeValue($url_level_totop,false);
            
             $url_parentType = $DB->EscapeValue($url_parentType,false);
            $url_level_totop = $DB->EscapeValue($url_level_totop,false);
            
        }
    }

?>
