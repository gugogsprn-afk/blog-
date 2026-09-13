<?php

    class UrlCache extends EntityBase {

        public $url = '';
        private static $itemTypes = array('root', 'helper', 'category', 'product', 'file', 'pages','galery');
        private static $suffixes = array('category' => '/', 'product' => '.html', 'pages'=>'.html','galery'=>'.html','file' => '*','folder'=>'/','html'=>'.html');

        public $ID = 0;

        public function __construct($itemID, $item_Type) {
            parent::__construct();
            $this->setProp('parent_id', _VARINT($itemID));
            $this->setProp('itemtype', $item_Type);
        }

        public function Bind($props) {
            if (array_isset($props)) {
            foreach ($props as $key => $value) {
                $this->Props[$key] = $value;
            }
                $this->ID = intval($props['id']);
            } else {
                $this->ID=0;
            }
        }

        public function Load($throw = true) {
            $DB = DatabaseProvider::provide();
            $row = $DB->Fetch("SELECT id,pid,parent_id,componenttype,itemtype,alias,url,urlkey,urltop,urllevel,meta_title,meta_keys,meta_descr,meta_title_lg1,meta_keys_lg1,meta_descr_lg1,meta_title_lg2,meta_keys_lg2,meta_descr_lg2,displaytext,meta_robots,date_modified,cache_allow
                                FROM #__urlcache WHERE parent_id=" . $this->getProp('parent_id') . " AND itemtype='" . $this->getProp('itemtype') . "'");
            if ($DB->RowCount() <= 0 && $throw === true)
                throw new Exception('Url not found');
            $this->Bind($row);
            return $row;
        }

        public function Insert() {
            $DB = DatabaseProvider::provide();
            $parent_id = _VARINT($this->getProp('parent_id'));
            if ($parent_id <= 0)
                throw new Exception('Url parent is null');
            $compType = $DB->EscapeValue($this->getProp('componenttype'), false);
            $itemtype = $this->getProp('itemtype');
            if (!in_array($itemtype, self::$itemTypes))
                throw new Exception('Invalid Url type');
            $alias = $DB->EscapeValue($this->getProp('alias'), false);
            $url = $DB->EscapeValue($this->getProp('url'), false);

            $exists = UrlCache::Exists($this->getProp('url'));
            if ($exists) {
                throw new EInvalidInputError(array(strtolower($itemtype).'-url-object'));
            }
            if (empty($alias) || empty($url)) {
                throw new EInvalidInputError(array(strtolower($itemtype) . '-url-object'));
            }

            $urlParent = $this->FindMyParent();
            $pid = _VARINT($urlParent->getProp('id'));
            $urllevel = _VARINT($urlParent->getProp('urllevel')) + 1;
            $urltop = $urlParent->getProp('urltop') . $pid . ',';

            $meta_Title = $DB->EscapeValue($this->getProp('meta_title'), false);
            $meta_keys = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys') , false), 1000, ',');
            $meta_Descr = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr') , false),200,' ');

            $meta_Title_lg1 = $DB->EscapeValue($this->getProp('meta_title_lg1'), false);
            $meta_keys_lg1 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys_lg1'), false), 1000, ',');
            $meta_Descr_lg1 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr_lg1'), false),200,' ');

            $meta_Title_lg2 = $DB->EscapeValue($this->getProp('meta_title_lg2'), false);
            $meta_keys_lg2 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys_lg2'), false), 1000, ',');
            $meta_Descr_lg2 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr_lg2'), false),200,' ');

            $displayText = $DB->EscapeValue($this->getProp('displaytext'), false);

            $metaRobots = _VARINT($this->getProp('meta_robots'));
            $cacheAllow = _VARINT($this->getProp('cache_allow'));
            
            
            $sql = "INSERT INTO #__urlcache SET
                    parent_id = " . $parent_id . ",
                    componenttype = '" . $compType . "',
                    voider='',    
                    itemtype = '" . $itemtype . "',
                    alias = '" . $alias . "',
                    url = '" . $url . "',
                    urlkey = CRC32('" . $url . "'),
                    pid = " . $pid . ",
                    date_modified = NOW(),
                    cache_allow = ".$cacheAllow.",
                    urltop = '" . $urltop . "',
                    urllevel =" . $urllevel . ",
                    displaytext='" . $displayText . "',
                    meta_title = '" . $meta_Title . "',
                    meta_keys = '" . $meta_keys . "',
                    meta_descr ='" . $meta_Descr . "',
                    meta_title_lg1 = '" . $meta_Title_lg1 . "',
                    meta_keys_lg1 = '" . $meta_keys_lg1 . "',
                    meta_descr_lg1 ='" . $meta_Descr_lg1 . "',
                    meta_title_lg2 = '" . $meta_Title_lg2 . "',
                    meta_keys_lg2 = '" . $meta_keys_lg2 . "',
                    meta_descr_lg2 ='" . $meta_Descr_lg2 . "',
                    meta_robots = ".$metaRobots;
            $ok = $DB->Query($sql);
            if ($ok == false)
                throw new Exception('Error inserting url');

            return true;
        }

        
        
        public function Update() {
            $DB = DatabaseProvider::provide();
            $parent_id = _VARINT($this->getProp('parent_id'));
            if ($parent_id <= 0)
                throw new Exception('Url parent is null');
            $itemtype = $this->getProp('itemtype');
            if (!in_array($itemtype, self::$itemTypes))
                throw new Exception('Invalid Url type');
            $alias = $DB->EscapeValue($this->getProp('alias'), false);
            $url = $DB->EscapeValue($this->getProp('url'), false);
            
            $oldID = _VARINT($this->getProp('id'));
            
            
            if (empty($alias) || empty($url)) {
                throw new EInvalidInputError(array(strtolower($itemtype) . '-url-object'));
            }

            $exists = intval($DB->Scalar("SELECT COUNT(*) FROM #__urlcache WHERE urlkey = CRC32('" . $url . "') AND url='" . $url . "' AND id<>".$oldID));
            if ($exists>0) {
                throw new EInvalidInputError(array(strtolower($itemtype) . '-url-object'));
            }
            
            
            // find url parent and change if need
            $oldLevel = _VARINT($this->getProp('urllevel'));
            $oldTotop = $this->getProp('urltop');
            $oldParent = _VARINT($this->getProp('pid'));
            
            
            $urlParent = $this->FindMyParent();
            $parentID = _VARINT($urlParent->getProp('id'));
            
            $parentChanged = false;
            $parentSQL = '';
            $level = _VARINT($urlParent->getProp('urllevel')) + 1;
            $totop = $urlParent->getProp('urltop') . $parentID . ',';
            if ($oldParent !== $parentID || $totop!==$oldTotop) {
                $parentChanged =  true;
                $parentSQL = "pid = " . $parentID . ",
                              urltop = '" . $totop . "',
                              urllevel =" . $level . ",";
            }
            
            $meta_Title = $DB->EscapeValue($this->getProp('meta_title'), false);
            $meta_keys = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys') , false), 1000, ',');
            $meta_Descr = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr') , false),200,' ');

            $meta_Title_lg1 = $DB->EscapeValue($this->getProp('meta_title_lg1'), false);
            $meta_keys_lg1 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys_lg1'), false), 1000, ',');
            $meta_Descr_lg1 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr_lg1'), false),200,' ');

            $meta_Title_lg2 = $DB->EscapeValue($this->getProp('meta_title_lg2'), false);
            $meta_keys_lg2 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_keys_lg2'), false), 1000, ',');
            $meta_Descr_lg2 = $this->stripMetas($DB->EscapeValue($this->getProp('meta_descr_lg2'), false),200,' ');

            $displayText = $DB->EscapeValue($this->getProp('displaytext'), false);
            $metaRobots = _VARINT($this->getProp('meta_robots'));
            
            $sql = "UPDATE #__urlcache SET
                        alias = '" . $alias . "',
                        url = '" . $url . "',
                        urlkey = CRC32('" . $url . "'),
                        date_modified = NOW(),
                        ".$parentSQL."
                        displaytext='" . $displayText . "',
                        meta_title = '" . $meta_Title . "',
                        meta_keys = '" . $meta_keys . "',
                        meta_descr ='" . $meta_Descr . "',
                        meta_title_lg1 = '" . $meta_Title_lg1 . "',
                        meta_keys_lg1 = '" . $meta_keys_lg1 . "',
                        meta_descr_lg1 ='" . $meta_Descr_lg1 . "',
                        meta_title_lg2 = '" . $meta_Title_lg2 . "',
                        meta_keys_lg2 = '" . $meta_keys_lg2 . "',
                        meta_descr_lg2 ='" . $meta_Descr_lg2 . "',
                        meta_robots = ".$metaRobots."    
                        WHERE id = ".$oldID;
            $ok = $DB->Query($sql);
            if ($ok == false)
                throw new Exception('Error updating url');
            
            if ($parentChanged===true) {
                $DB->Query("update #__urlcache set
                            urltop = REPLACE(urltop,'" . $oldTotop . "','" . $totop . "'),
                            urllevel = (urllevel+(" . $level . "-" . $oldLevel . "))
                     where pid=" . $oldID . " OR urltop LIKE CONCAT('%,'," . $oldID . ",',%')");
            }
            
            return true;
        }

        public function UpdateMetas($urlProps) {
            $DB = DatabaseProvider::provide();
            $parent_id = _VARINT($this->getProp('parent_id'));
            if ($parent_id <= 0)
                throw new Exception('Url parent is null');
            $itemtype = $this->getProp('itemtype');
            if (!in_array($itemtype, self::$itemTypes))
                throw new Exception('Invalid Url type');
           
            $meta_Title = $DB->EscapeValue($urlProps['meta_title'], false);
            $meta_keys = $this->stripMetas($DB->EscapeValue($urlProps['meta_keys'] , false), 1000, ',');
            $meta_Descr = $this->stripMetas($DB->EscapeValue($urlProps['meta_descr'] , false),200,' ');

            $meta_Title_lg1 = $DB->EscapeValue($urlProps['meta_title_lg1'], false);
            $meta_keys_lg1 = $this->stripMetas($DB->EscapeValue($urlProps['meta_keys_lg1'], false), 1000, ',');
            $meta_Descr_lg1 = $this->stripMetas($DB->EscapeValue($urlProps['meta_descr_lg1'], false),200,' ');

            $meta_Title_lg2 = $DB->EscapeValue($urlProps['meta_title_lg2'], false);
            $meta_keys_lg2 = $this->stripMetas($DB->EscapeValue($urlProps['meta_keys_lg2'], false), 1000, ',');
            $meta_Descr_lg2 = $this->stripMetas($DB->EscapeValue($urlProps['meta_descr_lg2'], false),200,' ');

            $displayText = $DB->EscapeValue($urlProps['displaytext'], false);
            $metaRobots = _VARINT($this->getProp('meta_robots'));
            
            $sql = "UPDATE #__urlcache SET
                        date_modified = NOW(),
                        displaytext='" . $displayText . "',
                        meta_title = '" . $meta_Title . "',
                        meta_keys = '" . $meta_keys . "',
                        meta_descr ='" . $meta_Descr . "',
                        meta_title_lg1 = '" . $meta_Title_lg1 . "',
                        meta_keys_lg1 = '" . $meta_keys_lg1 . "',
                        meta_descr_lg1 ='" . $meta_Descr_lg1 . "',
                        meta_title_lg2 = '" . $meta_Title_lg2 . "',
                        meta_keys_lg2 = '" . $meta_keys_lg2 . "',
                        meta_descr_lg2 ='" . $meta_Descr_lg2 . "',
                        meta_robots = ".$metaRobots."    
                        WHERE parent_id = " . $parent_id . " AND itemtype = '" . $itemtype . "'";
            $ok = $DB->Query($sql);
            if ($ok == false)
                throw new Exception('Error updating url');
            return true;
        }
        
        
        public function UpdateParentVisible($isVisible) {
            $DB = DatabaseProvider::provide();
            $parent_id = _VARINT($this->getProp('parent_id'));
            if ($parent_id <= 0)
                throw new Exception('Url parent is null');
            $itemtype = $this->getProp('itemtype');
            if (!in_array($itemtype, self::$itemTypes))
                throw new Exception('Invalid Url type');
            $urlID = _VARINT($DB->Scalar("SELECT id FROM #__urlcache WHERE parent_id = " . $parent_id . " AND itemtype = '" . $itemtype . "'"));
            $DB->Query("UPDATE #__urlcache SET parent_visible="._VARINT($isVisible)." WHERE id = " . $urlID);
            $DB->Query("UPDATE #__urlcache SET parent_visible="._VARINT($isVisible)." WHERE urltop LIKE CONCAT('%,',".$urlID.",',%')");
        }
        
        private function stripMetas($rawInput,$maxLength,$separator) {
            $stripped = $rawInput;
            if (mb_strlen($rawInput) > $maxLength) {
                $ps = mb_strrpos(mb_substr($rawInput, 0, $maxLength), $separator);
                if (!$ps)
                    $ps = $maxLength;
                $stripped = mb_substr($rawInput, 0, $ps);
            }
            return $stripped;
        }

        public function Remove() {
            $DB = DatabaseProvider::provide();
            $parent_id = _VARINT($this->getProp('parent_id'));
            if ($parent_id <= 0)
                throw new Exception('Url parent is null');
            $itemtype = $this->getProp('itemtype');
            if (!in_array($itemtype, self::$itemTypes))
                throw new Exception('Invalid Url type');

            $sql = "DELETE FROM #__urlcache WHERE parent_id = " . $parent_id . " AND itemtype = '" . $itemtype . "'";
            $ok = $DB->Query($sql);
            if ($ok == false)
                throw new Exception('Error deleting url');
            return true;
        }

        

        /**
         * @var UrlCache
         */
        private function FindMyParent() {
            $itemType = $this->getProp('itemtype');
            if (!in_array($itemType, self::$itemTypes))
                throw new Exception('Invalid Url type');

            $urlParent = UrlCache::FindUrl('/');
            $ItemDetails = UrlCache::FindItemDetails($itemType);

            if (!empty($ItemDetails['itemlocation'])) {
                $DB = DatabaseProvider::provide();
                $parent_id = _VARINT($this->getProp('parent_id'));
                $ItemParent = $DB->Scalar('SELECT parent_id FROM #__' . $ItemDetails['itemlocation'] . ' WHERE id=' . $parent_id);
                if ($ItemParent === false || $ItemParent===null || $DB->RowCount() <= 0) {
                    return $urlParent;
                } else {
                    if ($ItemDetails['parenttype']=='category' && _VARINT($ItemParent)===0) {
                        $CatRootPage = intval($DB->Scalar("SELECT id FROM #__pages WHERE alias='page-catalog'"));
                        if ($CatRootPage!==0) {
                            $ItemParent = $CatRootPage;
                            $ItemDetails['parenttype'] = 'pages';
                        }
                    }
                    $urlParent = new UrlCache($ItemParent, $ItemDetails['parenttype']);
                    $urlParent->Load(false);
                    if ($urlParent->ID===0) {
                        $urlParent = UrlCache::FindUrl('/');
                    }
                }
            }
            return $urlParent;
        }

        public static function FindRootCategory($currentItemId, $currentItemType) {
            $DB = DatabaseProvider::provide();
            $currentItemId = _VARINT($currentItemId);
            $rootID = $DB->Scalar("SELECT tp.parent_id
                            FROM #__urlcache tu
                            INNER JOIN #__urlcache tp ON tu.id=tp.id OR tu.urltop LIKE CONCAT('%,',tp.id,',%')
                            WHERE tu.parent_id=" . $currentItemId . " AND tu.itemtype='" . $currentItemType . "' AND tp.urllevel=2");
            if ($rootID === false)
                return false;
            $rootID = _VARINT($rootID);
            if ($rootID === 0)
                return false;
            return $rootID;
        }

        public static function FindItemDetails($itemType) {
            $details = array('itemlocation' => '', 'parenttype' => '');
            switch ($itemType) {
                case 'product':
                    $details['itemlocation'] = 'items';
                    $details['parenttype'] = 'category';
                    break;
                case 'category':
                    $details['itemlocation'] = 'categories';
                    $details['parenttype'] = 'category';
                    break;
                case 'galery':
                    $details['itemlocation'] = 'gallery';
                    $details['parenttype'] = 'pages';
                    break;
                case 'file':
                    $details['itemlocation'] = 'files';
                    $details['parenttype'] = 'item';
                case 'pages':
                    $details['itemlocation'] = 'pages';
                    $details['parenttype'] = 'pages';
                default:
                    break;
            }
            return $details;
        }

        public static function BuildUrl($alias, $itemType, $itemParentID, $mode,$params = null) {
            if (!in_array($itemType, UrlCache::$itemTypes))
                throw new Exception('Invalid Url type');
            $DB = DatabaseProvider::provide();
            $urlParent = UrlCache::FindUrl('/');
            
            $ItemDetails = UrlCache::FindItemDetails($itemType);
            if (!empty($ItemDetails['parenttype'])) {
                // $itemType category, $itemParentID 0, 
                if ($ItemDetails['parenttype']=='category' && _VARINT($itemParentID)===0) {
                    $CatRootPage = intval($DB->Scalar("SELECT id FROM #__pages WHERE alias='page-catalog'"));
                    if ($CatRootPage!==0) {
                        $itemParentID = $CatRootPage;
                        $ItemDetails['parenttype'] = 'pages';
                    }
                }
                $urlParent = new UrlCache($itemParentID, $ItemDetails['parenttype']);
                $urlParent->Load(false);
                if ($urlParent->ID===0) {
                    $urlParent = UrlCache::FindUrl('/');
                }
            }
            $suffixKey = $itemType;
            if (array_isset($params)) {
                if (array_key_exists('urltype', $params)) {
                    if (array_key_exists($params['urltype'], UrlCache::$suffixes)) {
                        $suffixKey = $params['urltype'];
                    } else {
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Wrong urltype parameter');
                    }
                } 
            }
            
            $suff = UrlCache::$suffixes[$suffixKey];
            
            

            $parentUrl = $urlParent->getProp('url');
            if ($parentUrl==='/') {
                $parentUrl = '';
            }
            if (array_isset($params)) {
                if (array_key_exists('urlpath', $params)) {
                    $parentUrl = $params['urlpath'];
                }
            }

            if ($suff === '*') {
                $suff = '';
                $urlLength = strlen($parentUrl);
                if (substr($parentUrl, $urlLength - 5, 5) === '.html') {
                    $parentUrl = substr($parentUrl, 0, $urlLength - 5) . '/';
                } else if (substr($parentUrl, $urlLength - 4, 4) === '.cmd') {
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'Wrong url parent');
                } else if (substr($parentUrl, $urlLength - 1, 1) === '/') {
                    // 
                } else {
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'Unknown url parent');
                }
            }


            $url = $parentUrl . $alias . $suff;
            
            $MaxCnt = $mode == 'add' ? 0 : 1;
            
            $itemid = intval($params['itemid']);
            $addSQL='';
            if ($mode=='edit' && $itemid>0) {
                $MaxCnt = 0;
                $addSQL = " AND parent_id<>".$itemid." AND itemtype='".$itemType."'";
            }
            
            $exists = $DB->Scalar("SELECT COUNT(*) FROM #__urlcache WHERE urlkey = CRC32('" . $url . "') AND url='" . $url . "'".$addSQL);
            $retArr = array('url' => $url);
            if (intval($exists) > $MaxCnt) {
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Url already exists');
            }
            return $retArr;
        }

        /**
         * 
         * @param type $url
         * @throws EPageError
         * @var UrlCache
         */
        public static function FindUrl($url) {
            $DB = DatabaseProvider::provide();
            $url = $DB->EscapeValue($url);
            $row = $DB->Fetch("SELECT id, pid, parent_id, componenttype, voider, itemtype, alias, displaytext, url, urlkey, visible, urltop, urllevel,meta_robots FROM #__urlcache WHERE urlkey = CRC32(" . $url . ") AND url=" . $url);
            if ($row === false)
                throw new EPageError(EPageError::PAGE_NOT_FOUND, $url . ' url not found');
            if ($DB->RowCount() <= 0)
                throw new EPageError(EPageError::PAGE_NOT_FOUND, $url . ' url not found');
            $found = new UrlCache($row['parent_id'], $row['itemtype']);
            $found->Bind($row);
            return $found;
        }

        public static function Exists($url) {
            $DB = DatabaseProvider::provide();
            $exists = $DB->Scalar("SELECT COUNT(*) FROM #__urlcache WHERE urlkey = CRC32('" . $url . "') AND url='" . $url . "'");
            if (intval($exists) > 0)
                return true;
            return false;
        }
        
       
        
        public static function getMapJson($pid) {
            $DB = DatabaseProvider::provide();
            $qry = "SELECT tc.id,tc.pid,tc.parent_id,tc.componenttype,tc.itemtype,tc.displaytext,tc.url as topath,tc.urlkey,tc.urltop,tc.urllevel,count(DISTINCT td.id) as child_count
                        FROM #__urlcache tc
                        LEFT JOIN #__urlcache td ON td.pid = tc.id and td.visible=1
                        WHERE tc.pid = ".intval($pid)." AND tc.visible=1
                        GROUP by tc.id
                        ORDER by tc.displaytext";
            $DB->Query($qry);
            $itemArr = array();
            if ($DB->RowCount()>0) {
                while ($row = $DB->ReadRow()) {
                    $row['Children'] =  (_VARINT($row['child_count'])>0?true:false);
                    $itemArr[] = $row;
                }
            }
            /*
            $arr = array(); //$ItemCollection->toArray();
            if (count($arr)<=0) {
                $arr[] = array('id'=>0,'parent_id'=>0,'name'=>'Catalog','totop'=>'','nodelevel'=>'-1','pos'=>0,'visible'=>'1','visiblemark'=>'','showup'=>'0','showdown'=>'0');
            }*/
            return SerilizeToJson($itemArr);
            
        }

    }

?>
