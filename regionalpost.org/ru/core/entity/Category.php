<?php
/**
 * 
 *
 * @author Sight©
 */
class Category extends SiteEntity {
    
    protected $table = '#__categories';
    protected $idField = 'id';
    protected $FieldArray = Array();
    protected $CollectionFieldArray = Array();
   


    public function __construct($mId) {
        parent::__construct($mId);
        $this->FieldArray =  Array('tc.id','tc.parent_id','tc.name_#','tc.descr_#','tc.content_#','tu.url as url',"CASE WHEN tu.meta_robots=0 THEN 'true' ELSE '' END as mfol");
        $this->CollectionFieldArray = Array('tc.id','tc.parent_id','tc.name_#',"tu.url","CASE WHEN tu.meta_robots=0 THEN 'true' ELSE '' END as mfol");
    }
    
    protected function OnLoad($success) {
        parent::OnLoad($success);
        if ($success) {
            if ($this->IsCollectionItem) {
                
            } else {
                
            }
            $this->setProp('name', mb_str_replace('&', '&amp;', $this->getProp('name')) );
        }
    }
    
    
    
    protected function getSelectQuery() {
        $this->id = intval($this->ID);
        $qry = new QueryBuilder();
        $qry->Select($this->FieldArray,$this->table.' tc')->Join('#__urlcache tu', "tu.parent_id=tc.id AND tu.itemtype='category'",'INNER')->Where('tc.visible=1 AND tc.id='.$this->ID);
        return $qry->GetQuery();
    }
    
    

    public function Collection($AsArray = false) {
        $cls = get_class ($this);
        if ($AsArray) $cls = '';
        $fields = $this->CollectionFieldArray==null?$this->FieldArray:$this->CollectionFieldArray;
        $collection = new DataCollectionAdapter($cls,$this->table,$this->idField,$fields,$this->Props);
        $qry = new QueryBuilder();
        $filters = $collection->getFilters();
        if (empty($filters)) {
            $filters = ' tc.visible=1 '; 
        } else {
            $filters = $filters.' AND tc.visible=1 ';
        }
        $sql =  $qry->Select($fields, '#__categories tc')
                    ->Join('#__urlcache tu', "tu.parent_id=tc.id AND tu.itemtype='category'",'INNER')
                    ->Where($filters)->GetQuery();
        $collection->SetQuery($sql);
        $collection->ordering = 'tc.nodelevel,tc.pos';
        return $collection;
    }
    
    public function getList($filters) {
        $qry = new QueryBuilder();
        if (empty($filters)) {
            $filters = ' tc.visible=1 '; 
        } else {
            $filters = $filters.' AND tc.visible=1 ';
        }
        $sql =  $qry->Select(array('tc.id','tc.name_#','tu.url'), '#__categories tc')
                    ->Join('#__urlcache tu', "tu.parent_id=tc.id AND tu.itemtype='category'",'INNER')
                    ->Where($filters)->OrderBy('tc.pos')->GetQuery();
        $DB = DatabaseProvider::provide();
        $DB->Query($sql);
        $ret = array();
        if ($DB->RowCount()>0) {
            while($row=$DB->ReadRow()) {
                $row['id'] = intval($row['id']);
                $row['url'] = HttpContext::current()->culture()->LanguagePath() .$row['url'];
                $ret[$row['id']] = $row;
            }
        }
        return $ret;
    }
    
    
    /**
     * 
     * @return DataCollectionAdapter
     */
    public static function GetCollection($conds,$asArray=false) {
        $cat = new Category(null);
        foreach ($conds as $key=>$value) {
            $cat->setProp($key, $value);
        }
        return $cat->Collection($asArray);
    }
    
    
    public static function exists($id) {
        $DB = DatabaseProvider::provide();
        $r =intval($DB->Scalar("SELECT id FROM #__categories WHERE id=".intval($id)));
        return $r>0;
    }
    /*
    public function getPath($includeSelf = true) {
        $id = _VARINT($this->ID);
        $inc = $includeSelf?' tc.id=tp.id or ':'';
        $qry = "SELECT tp.id,tp.name,tu.url
                    FROM #__categories tc 
                    INNER JOIN #__categories tp ON ".$inc." tc.totop LIKE CONCAT('%,',tp.id,',%')
                    inner join #__urlcache tu on tu.parent_id=tp.id and tu.itemtype='category'
                    WHERE tc.id=".$id." AND tc.visible=1 AND tp.visible=1
                    order by tp.nodelevel";
        $DB = DatabaseProvider::provide();
        $ok = $DB->Query($qry);
        if ($ok==false) return array();
        if ($DB->RowCount()<=0) return array();
        $ret = $DB->ReadAll();
        end($ret);
        $ret[key($ret)]['last'] = 'true';
        return $ret;
    }
    */
    
    /*
    public function getItemsAsTree() {
        $qry = new QueryBuilder();
        $filters = $qry->BuildConditions($this->Props);
        if (empty($filters)) {
            $filters = ' tc.visible=1 '; 
        } else {
            $filters = $filters.' AND tc.visible=1 ';
        }
        $sql =  $qry->Select($this->CollectionFieldArray, '#__categories tc')
                    ->Join('#__urlcache tu', "tu.parent_id=tc.id AND tu.itemtype='category'",'INNER')
                    ->Where($filters)->OrderBy('tc.nodelevel,tc.pos')->GetQuery();
        $DB = DatabaseProvider::provide();
      //  echo $sql;
        $DB->Query($sql);
        $ret = array();
        if ($DB->RowCount()>0) {
            while ($row = $DB->ReadRow()) {
                $row['name'] = mb_str_replace('&', '&amp;', $row['name'] );
                if (intval($row['parent_id'])>0) {
                    $ret['cat'.intval($row['parent_id'])]['childs'][] = $row;
                } else {
                    $row['letter'] = mb_substr($row['name'], 0, 1);
                    $ret['cat'.intval($row['id'])] = $row;
                }
            }
        }
        return $ret;
    }
    */
    /*
    public static function loadTree($parentID,$preserveKeys = false) {
        $sql = "SELECT tp.id, tp.parent_id, tp.name_# as text,CONCAT('".HttpContext::current()->request()->Language."',tu.url) as url
                    FROM #__categories tc
                    INNER JOIN #__categories tp ON tp.id=tc.id OR tp.parent_id = tc.id OR tp.totop LIKE CONCAT('%,',tc.id,',%')
                    INNER JOIN #__urlcache tu ON tu.parent_id=tp.id AND tu.itemtype='category'
                    WHERE tp.visible=1 AND tc.id = ".  _VARINT($parentID)."
                    ORDER BY tp.nodelevel,tp.pos";
        if (_VARINT($parentID)===0) {
            $sql = "SELECT tp.id, tp.parent_id, tp.name_# as text,CONCAT('".HttpContext::current()->request()->Language."',tu.url) as url
                    FROM #__categories tp 
                    INNER JOIN #__urlcache tu ON tu.parent_id=tp.id AND tu.itemtype='category'
                    WHERE tp.visible=1
                    ORDER BY tp.nodelevel,tp.pos";
        } 
        $DB = DatabaseProvider::provide();
        $DB->Query($sql);
        
        $tree = array(
            'items' => array(),
        );
        
        if ($DB->RowCount()>0) {
            if ($preserveKeys===true) {
                $index = array(0=>&$tree);
                while ($row = $DB->ReadRow()) {
                    // pick the parent node inside the tree by using the index
                    $parent = &$index[$row['parent_id']];
                    $parent['items'][$row['id']] = $row;
                    // insert/update reference to recently inserted node inside the tree
                    $index[$row['id']] = &$parent['items'][$row['id']];
                }
            } else {
                $data = $DB->ReadAll();
                $tree['items'] = createTree($data);
            }
        }
        
        return $tree['items'];
    }
    */
    

}
