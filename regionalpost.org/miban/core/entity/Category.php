<?php

    class Category extends TableEditableItem {

        /* position update
         * UPDATE tb_itemprops tu,
            (SELECT tf.id,tf.parent_id,tf.name,tf.itemvalue,@rank:=CASE WHEN @parent_id <> tf.parent_id THEN 1 ELSE @rank+1 END AS rn,@parent_id:=tf.parent_id AS parent_id1
            FROM (SELECT @rank:= -1) r,(SELECT @parent_id:= '') p,(SELECT * FROM tb_itemprops nt1 ORDER BY nt1.parent_id,nt1.id) tf) tmain
            SET tu.itemvalue = tmain.rn
            WHERE tu.id = tmain.id
         */
        // must override
        protected $table = ' #__categories';
        protected $idField = 'id';
        protected $FieldArray = Array('id', 'parent_id', 'name','descr','content', 'totop', 'nodelevel', 'pos', 'visible','name_lg1','name_lg2','descr_lg1','descr_lg2','content_lg1','content_lg2');
        protected $CollectionFieldArray = Array('tc.id', 'tc.parent_id', 'tc.name', 'tc.totop', 'tc.nodelevel', 'tc.pos', 'tc.visible','count(DISTINCT td.id) as child_count');
        protected $ValidationFields = Array('name'=>'!null');

        private $allowCaching = 0;


        public function __construct($mId) {
            parent::__construct($mId);
        }
        /**
         *
         * @var UrlCache
         */
        public $url = null;

        protected function OnLoad($success) {
            parent::OnLoad($success);
            if ($success) {
                if ($this->IsCollectionItem) {
                    $this->setProp('Children', (_VARINT($this->getProp('child_count'))>0?true:false));
                    // $this->setProp('pageurl', 'HTTP://'.$_SERVER['HTTP_HOST'].'/'.$this->getProp('alias').$this->getProp('linktype'));
                } else {
                    $this->url = new UrlCache($this->ID,'category');
                    $this->url->Load();
                    $path = trim($this->getProp('totop'), ',');
                    $fullPath = trim($this->getProp('totop').$this->ID,',');
                    $this->setProp('fullpath', $fullPath);
                    $this->setProp('path', $path);
                }
            }
        }
        private $nid = 0;
        public function getJsonCollectionOv($nid) {
            $this->nid  = _VARINT($nid);
            $ItemCollection = $this->Collection();
            $ItemCollection->ordering ='pos';

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
            $sql = $qry->Select($this->CollectionFieldArray, '#__categories tc')->Join('#__categories td', 'td.parent_id =tc.id')->Where($collection->getFilters().' AND tc.id<>'.$this->nid)->GroupBy('tc.id')->GetQuery();
            $collection->SetQuery($sql);
            $DB = DatabaseProvider::provide();
            $poses = $DB->Fetch('SELECT max(pos) as maxpos,min(pos) as minpos FROM '.$this->table.' WHERE parent_id='.  _VARINT($this->getProp('parent_id')));
            if ($poses==false)
                throw new EPageError(EPageError::CUSTOM_ERROR,'failed find positions in collection');
            $collection->setConstants($poses);
            return $collection;
        }

        public function GetAll() {
            $qry = new QueryBuilder();
            $sql = $qry->Select($this->CollectionFieldArray, '#__categories tc')->Join('#__categories td', 'td.parent_id =tc.id')->GroupBy('tc.id')->GetQuery();
            $DB = DatabaseProvider::provide();
            return $DB->Fill($sql);
        }

        public function  GetAllWithChildren() {
            $DB = DatabaseProvider::provide();
            return $DB->Fill("WITH RECURSIVE category_paths AS (
                SELECT  id,NAME, CAST(NAME AS CHAR(255)) AS path
                FROM tb_categories WHERE parent_id = 0
                UNION ALL
                SELECT  c.id,  c.name,  CONCAT(cp.path, '/', c.name) AS path
                FROM tb_categories c
                JOIN category_paths cp ON c.parent_id = cp.id
            )
            SELECT id, path as name FROM category_paths ORDER BY id;
            ");
        }

        public function UpdateFromOv($itemProps,$urlItemProps) {
             $pid = _VARINT($itemProps['parent_id']);

             $cat = new Category($this->ID);
             $cat->Load();
             $oldLevel = _VARINT($cat->getProp('nodelevel'));
             $oldTotop = $cat->getProp('totop');
             $oldParent = _VARINT($cat->getProp('parent_id'));
             unset($cat);
             if ($pid>0) {
                 $cat = new Category($pid);
                 $cat->Load();
                 $itemProps['nodelevel'] = _VARINT($cat->getProp('nodelevel'))+1;
                 $itemProps['totop'] = $cat->getProp('totop').$cat->ID.',';
             } else {
                 $itemProps['nodelevel'] = 1;
                 $itemProps['totop'] = ',';
             }
             $db = DatabaseProvider::provide();
             if ($pid!=$oldParent) {
                $maxpos = $db->Scalar('SELECT max(pos) as maxpos FROM '.$this->table.' WHERE parent_id='.$pid);
                if ($poses===false)
                    throw new EPageError(EPageError::CUSTOM_ERROR,'failed find new position for item');

                $itemProps['pos'] = intval($maxpos)+1;
             }
             $ok = parent::UpdateFrom($itemProps);
             if ($pid!=$oldParent) {
                $qry = "update ".$this->table." set
                               totop = REPLACE(totop,'".$oldTotop."','".$itemProps['totop']."'),
                               nodelevel = (nodelevel+(".$itemProps['nodelevel']."-".$oldLevel."))
                        where parent_id=".$this->ID." OR totop LIKE CONCAT('%,',".$this->ID.",',%')";
                $db->Query($qry);
             }
             $this->url = new UrlCache($this->ID,'category');
             $urlItemProps['displaytext'] = $itemProps['name'];
             $this->url->Load();
             $this->url->Bind($urlItemProps);
             $this->url->Update();
             return $ok;
        }

        public function InsertFromOv($itemProps,$urlItems) {
            $pid = _VARINT($itemProps['parent_id']);
             if ($pid>0) {
                 $cat = new Category($pid);
                 $cat->Load();
                 $itemProps['nodelevel'] = _VARINT($cat->getProp('nodelevel'))+1;
                 $itemProps['totop'] = $cat->getProp('totop').$cat->ID.',';
             } else {
                 $itemProps['nodelevel'] = 1;
                 $itemProps['totop'] = ',';
             }
             $filterList = array('parent_id'=>$pid);
             $ok = parent::InsertFrom($itemProps, $filterList);

             try {
                $this->url = new UrlCache($this->ID,'category');
                $urlItems['displaytext'] = $itemProps['name'];
                $urlItems['cache_allow'] = $this->allowCaching;
                $this->url->Bind($urlItems);
                $this->url->Insert();
             } catch (Exception $ex) {
                parent::Remove();
                if (!empty($this->ID)) {
                    $this->url = new UrlCache($this->ID,'category');
                    $this->url->Remove();
                }
                throw $ex;
            }

            return $ok;
        }

        public function Remove() {

            $db = DatabaseProvider::provide();

            $childCatsCount =_VARINT( $db->Scalar('SELECT COUNT(*) FROM '.$this->table.' WHERE parent_id = '.$this->ID));
            if ($childCatsCount>0)
                throw new EPageError(EPageError::CUSTOM_ERROR,'Category contains child categories, delete them first');

            $childItemsCount = _VARINT($db->Scalar('SELECT COUNT(*) FROM #__items_cats WHERE parent_id = '.$this->ID));
            if ($childItemsCount>0)
                throw new EPageError(EPageError::CUSTOM_ERROR,'Category contains items, delete them first');

            $ok = parent::Remove();
            $this->url = new UrlCache($this->ID,'category');
            $this->url->Remove();
            return $ok;
        }

        public function ChangeVisible($value) {
            $this->setProp('visible', $value);
            $ok = $this->Update();
            if ($ok == false) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'item not found');
            }
            $qry = new QueryBuilder();
            $sql = $qry->Update(array('visible'), $this->table, array('visible'=>$value))->Where("totop like CONCAT('%,',".$this->ID.",',%')")->GetQuery();
            DatabaseProvider::provide()->Query($sql);
            $url = new UrlCache($this->ID,'category');
            $url->UpdateParentVisible($value);
            return '"result" : "ok"';
        }


        public static function getKeyAliases() {
            $DB = DatabaseProvider::provide();
            $DB->Query("SELECT keyalias FROM #__categories WHERE keyalias<>''");
            $tags = '';
            if ($DB->RowCount()<=0)
                return '';

            while ($value = $DB->ReadRow()) {
                $tags = $tags . '<option value="' . $value['keyalias'] . '">' . $value['keyalias'] . '</option>';
            }

            return $tags;
        }


    }

?>
