<?php
/**
 *
 *
 * @author Sight©
 */
class Page extends SiteEntity {

    protected $table = '#__pages';
    protected $idField = 'id';
    protected $FieldArray = Array();
    protected $CollectionFieldArray = Array();

    public $PageAlias = '';

    public function __construct($mId) {
        parent::__construct($mId);
        $this->FieldArray =  Array('tp.id', 'tp.name_#','tp.descr_#','tp.content_#','tp.visible','tp.block_id','tf.thumbname','tf.filename',"CONCAT('".HttpContext::current()->culture()->Language()."/',tu.url) as url","CASE WHEN tu.meta_robots=0 THEN 'true' ELSE '' END as mfol",'tp.date_object_$','tp.parent_id','tp.tags','tp.tagsb','tp.alias','tp.ainfo_#','tp.binfo_#',
            'YEAR(tp.date_object) AS date_year','MONTH(tp.date_object) AS date_month','DAY(tp.date_object) AS date_day',
            'tp.price','tp.intsa','tp.intsb','tp.intsc', 'tp.cinfo_#');
        $this->CollectionFieldArray = Array('tp.id', 'tp.name_#','tp.descr_#','tp.content_#','tp.visible','tp.block_id',"CONCAT('".HttpContext::current()->culture()->Language()."/',tu.url) as url","CASE WHEN tu.meta_robots=0 THEN 'true' ELSE '' END as mfol",'tf.thumbname','tf.filename','tf.img_width','tf.img_height','tp.date_object_$','tp.parent_id','tp.tags','tp.tagsb','tp.alias','tp.ainfo_#','tp.binfo_#',
            'YEAR(tp.date_object) AS date_year','MONTH(tp.date_object) AS date_month','DAY(tp.date_object) AS date_day',
            'tp.price','tp.intsa','tp.intsb','tp.intsc', 'tp.cinfo_#');
    }


    /**
     *
     * @return Page
     */
    public static function loadFixed($alias) {
        $page = new Page($alias);
        $page->PageAlias = $alias;
        $page->Load();
        $page->ID = _VARINT($page->getProp('id'));
        return $page;
    }


    /**
     *
     * @return Page
     */
    public static function loadSingle($id) {
        $page = new Page(intval($id));
        $page->Load();
        return $page;
    }



    /**
     *
     * @return DataCollectionAdapter
     */
    public static function BlockCollection($blockID,$asArray=false) {
        $page = new Page(null);
        $page->setProp('block_id', $blockID);
        $collection = $page->Collection($asArray);
        return $collection;
    }


    public static function LoadBlockCollection($blockID,$asArray=false,$useIdAsKey = false, $startIndex = 0, $rowCount = 0, $onlyVisibleItems = true,$ordering = '') {
        $page = new Page(null);
        $page->setProp('block_id', $blockID);
        $collection = $page->Collection($asArray);
        if (!empty($ordering)) {
            $collection->ordering = $ordering;
        }
        return $collection->Load($useIdAsKey,$startIndex,$rowCount,$onlyVisibleItems);
    }

    public static function LoadChildCollection($parentID,$asArray=false,$useIdAsKey = false, $startIndex = 0, $rowCount = 0, $onlyVisibleItems = true,$ordering = '') {
        $page = new Page(null);
        $page->setProp('tp.parent_id', $parentID);
        $collection = $page->Collection($asArray);
        if (!empty($ordering)) {
            $collection->ordering = $ordering;
        }
        return $collection->Load($useIdAsKey,$startIndex,$rowCount,$onlyVisibleItems);
    }

    protected function OnLoad($success) {
        parent::OnLoad($success);
        if ($success) {
            if ($this->IsCollectionItem) {

            } else {

            }
            $this->setProp('name', mb_str_replace('&', '&amp;', $this->getProp('name')));
            $this->setProp('date_mname', Monthshort(_VARINT($this->getProp('date_month')), HttpContext::current()->culture()->Language()));
            $this->setProp('date_mday',str_pad(_VARINT($this->getProp('date_day')),2,'0'));

            $this->setProp('pricef',number_format(floatval($this->getProp('price')),0,'.',' '));
            $this->setProp('intsaf',number_format(intval($this->getProp('intsa')),0,'.',' '));
        } else {
            throw new EPageError(EPageError::PAGE_NOT_FOUND);
        }
    }




    protected function getSelectQuery() {
        $this->ID = intval($this->ID);
        $where = 'tp.visible=1 AND tp.id='.$this->ID;
        if (empty($this->ID)) {
            if (empty($this->PageAlias)) {
                throw new EPageError(EPageError::PAGE_NOT_FOUND);
            } else {
                $where = "tp.visible=1 AND tp.alias='".$this->PageAlias."'";
            }
        }
        $qry = new QueryBuilder();
        $qry->Select($this->FieldArray,$this->table.' tp')
                ->Join('#__urlcache tu', "tu.parent_id=tp.id AND tu.itemtype='pages'",'LEFT')
                ->Join('#__files tf',"tf.type='pages' AND tf.parent_id=tp.id",'LEFT')
                ->Where($where);
        return $qry->GetQuery();
    }

    /**
     *
     * @return DataCollectionAdapter
     */
    public function Collection($AsArray = false,$customFilters='') {
        $cls = get_class ($this);
        if ($AsArray) $cls = '';
        $fields = $this->CollectionFieldArray==null?$this->FieldArray:$this->CollectionFieldArray;
        $collection = new DataCollectionAdapter($cls,$this->table,$this->idField,$fields,$this->Props);
        $qry = new QueryBuilder();
        if (empty($customFilters)) {
            $filters = $collection->getFilters();
            if (empty($filters)) {
                $filters = ' tp.visible=1 ';
            } else {
                $filters = $filters.' AND tp.visible=1 ';
            }
        } else {
            $filters = $customFilters;
        }
        $sql =  $qry->Select($fields, '#__pages tp')
                    ->Join('#__urlcache tu', "tu.parent_id=tp.id AND tu.itemtype='pages'",'LEFT')
                    ->Join('#__files tf',"tf.type='pages' AND tf.parent_id=tp.id",'LEFT')
                    ->Where($filters)->GetQuery();
        $collection->SetQuery($sql);
        $collection->ordering = 'tp.pos';
        return $collection;
    }

    public function loadTree($parent,$child,$selectedID) {
        $DB = DatabaseProvider::provide();

        $qry = new QueryBuilder();
        $qry->Select($this->CollectionFieldArray, '#__pages tp')
                    ->Join('#__urlcache tu', "tu.parent_id=tp.id AND tu.itemtype='pages'",'LEFT')
                    ->Join('#__files tf',"tf.type='pages' AND tf.parent_id=tp.id",'LEFT')
                    ->Where("tp.visible=1 AND tp.block_id IN (".$DB->EscapeValue($parent).",".$DB->EscapeValue($child).")")
                    ->OrderBy('CASE WHEN tp.block_id = '.$DB->EscapeValue($parent).' THEN 0 ELSE 1 END,tp.pos');

        $DB->Query($qry->GetQuery());
        $tbl = array();
        $keys = array();
        $idx = 0;
        while ($row = $DB->ReadRow()) {
            if (intval($row['id'])==intval($selectedID)) {
                $row['selected'] = 'selected';
            }
            if ($row['block_id']==$parent) {
                $row['items'] = array();
                $tbl[$idx] = $row;
                $keys[$row['id']] = $idx;
                $idx++;
            } else {
                // $tbl[$row['parent_id']]['items'][] = $row;
                $tbl[$keys[$row['parent_id']]]['items'][] = $row;
            }

        }
        return $tbl;
    }


    public function findByTag($blockName,$tag) {
        $DB = DatabaseProvider::provide();
        $where = 'tp.visible=1 AND tp.block_id='.$DB->EscapeValue($blockName)." AND tp.tags LIKE '%,".$tag.",%'";
        $qry = new QueryBuilder();
        $qry->Select($this->FieldArray,$this->table.' tp')
                ->Join('#__urlcache tu', "tu.parent_id=tp.id AND tu.itemtype='pages'",'LEFT')
                ->Join('#__files tf',"tf.type='pages' AND tf.parent_id=tp.id",'LEFT')
                ->Where($where)->OrderBy('tp.pos')->Limit(0, 3);
        $sql = $qry->GetQuery();
        return $DB->Fill($sql);
    }



    public function getFiles($filetype) {
        $iid = intval($this->ID);
        $DB = DatabaseProvider::provide();

        $DB->Query("SELECT id, parent_id, type, filename, thumbname, isdefault, img_width, img_height, tmb_width, tmb_height, mime_type, mime_thumb, descr_# as descr, tag, pos FROM #__files WHERE type=".$DB->EscapeValue($filetype)." AND parent_id=".$iid." ORDER BY pos ASC, id ASC");

        $ret = array();
        if ($DB->RowCount()<=0) {
            return $ret;
        }
        while ($row = $DB->ReadRow()) {
            $ret[] = $row;
        }
        return $ret;
    }

    public static function loadFiles($parentIDS) {
        if (!array_isset($parentIDS)) return array();
        $DB = DatabaseProvider::provide();
        $DB->Query("SELECT * FROM #__files WHERE type='galery' AND parent_id IN (".implode(',', $parentIDS).")");

        $ret = array();
        if ($DB->RowCount()<=0) {
            return $ret;
        }
        while ($row = $DB->ReadRow()) {
            if (!array_key_exists($row['parent_id'], $ret)) {
                $ret[$row['parent_id']] = array();
            }
            $ret[$row['parent_id']][] = $row;
        }
        return $ret;
    }

    public function parseSpecial() {

    }

    public function search($itemProps, $startIndex = 0, $rowCount = 0, $ordering = '',$requestTotal = true,$useIdAsKey = false) {
        $DB = DatabaseProvider::provide();

        $where = array();
        $fields = $this->CollectionFieldArray;
        if (!empty($itemProps['word'])) {
            $word = $DB->EscapeValue($itemProps['word'], false);

            $likeWhere = "tp.name LIKE '%".$word."%' OR tp.content LIKE '%".$word."%' OR tp.name_lg1 LIKE '%".$word."%' OR tp.content_lg1 LIKE '%".$word."%' OR tp.name_lg2 LIKE '%".$word."%' OR tp.content_lg2 LIKE '%".$word."%'";

            $fields[] = "MATCH(tp.name_#,tp.content_#) AGAINST ('".$word."') AS rel";
            $where[] = "(MATCH(tp.name_#,tp.content_#) AGAINST ('".$word."'  IN BOOLEAN MODE) OR FIND_IN_SET('".$word."',tp.tags)) OR ".$likeWhere;
            $ordering = 'rel DESC';
            // $ordering = 't_match DESC';
            // $where[] = "tp.name LIKE '%".$word."%' OR tp.content LIKE '%".$word."%' OR tp.name_lg1 LIKE '%".$word."%' OR tp.content_lg1 LIKE '%".$word."%' OR tp.name_lg2 LIKE '%".$word."%' OR tp.content_lg2 LIKE '%".$word."%'";
        }
        if (!empty($itemProps['block_id'])) {
            $where[] = "tp.block_id=".$DB->EscapeValue($itemProps['block_id']);
        }
        $joinCats = '';
        if (intval($itemProps['cat'])>0) {
            //$joinCats = 'INNER JOIN #__items_cats tci ON tci.item_id = tp.id';
            //$where[] = 'tci.parent_id = '.intval($itemProps['cat']);
            $where[]=' EXISTS (SELECT 1 FROM #__items_cats t2 WHERE t2.item_id=tp.id AND t2.parent_id='.intval($itemProps['cat']).')';
        }

        if (intval($itemProps['ncat'])>0) {
            $where[]=' NOT EXISTS (SELECT 1 FROM #__items_cats t2 WHERE t2.item_id=tp.id AND t2.parent_id='.intval($itemProps['ncat']).')';
        }


        if (!empty($itemProps['ids'])) {
            $where[] = 'tp.id IN ('.$itemProps['ids'].')';
        }

        if (!empty($itemProps['nids'])) {
            $where[] = 'tp.id NOT IN ('.$itemProps['nids'].')';
        }

        if (empty($ordering)) {
            $ordering = 'tp.id DESC';
        }
        if ($ordering=='OK') {
            $ordering = '';
        }
        $tbl = array('items'=>array(),'total'=>0);
        if (count($where)<=0) {
            return $tbl;
        }

        $whereRaw = implode(' AND ', $where);

        $qry = new QueryBuilder();
        $qry->Select($fields, '#__pages tp')
                    ->Join('#__urlcache tu', "tu.parent_id=tp.id AND tu.itemtype='pages'",'INNER')
                    ->AppendQuery($joinCats)
                    ->Join('#__files tf',"tf.type='pages' AND tf.parent_id=tp.id",'LEFT')
                    ->Where("tp.visible=1 AND ".$whereRaw);
        if (!empty($ordering)) {
            $qry->OrderBy($ordering);
        }
        if ($rowCount>0) {
            $qry->Limit($startIndex, $rowCount);
        }

        $DB->Query($qry->GetQuery());

        if ($useIdAsKey) {
            while ($row = $DB->ReadRow()) {
                $tbl['items'][intval($row['id'])] = $row;
            }
        } else {
            while ($row = $DB->ReadRow()) {
                $tbl['items'][] = $row;
            }
        }

        if ($requestTotal) {
            $tbl['total'] = intval($DB->Scalar("SELECT count(1) FROM #__pages tp INNER JOIN #__urlcache tu ON tu.parent_id=tp.id AND tu.itemtype='pages' ".$joinCats." WHERE tp.visible=1 AND (".$whereRaw.")"));
        } else {
            return $tbl['items'];
        }

        return $tbl;
    }




    public static function loadByAlias($alias) {
        $DB = DatabaseProvider::provide();
        return $DB->Fetch("SELECT name,name_lg1,name_lg2,content,content_lg1,content_lg2 FROM #__pages WHERE alias = ".$DB->EscapeValue($alias));
    }

}
?>
