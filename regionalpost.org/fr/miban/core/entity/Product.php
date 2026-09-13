<?php

    class Product extends TableEditableItem {

        // must override ( unusable - manufact,tags,servicelist )
        protected $table = ' #__items';
        protected $idField = 'id';
        protected $FieldArray = array('id', 'parent_id', 'parent_name' ,'name', 'descr', 'content', 'pos', 'visible' , 'geo_lat','geo_long','geo_zoom', 'adres','manufact','phone'
            , 'tags', 'propsa', 'payments' , 'stars', 'city','starsoff','propsb', 'propsc', 'workdescr','startprice' );
        protected $CollectionFieldArray = array('id', 'parent_id', 'manufact', 'name', 'pos', 'visible');
        protected $ValidationFields = Array('name' => '!null');
        private $translateFields = array('name', 'content', 'manufact', 'descr', 'adres', 'city','workdescr', 'parent_name');
        protected $trustFields = array('geo_lat','geo_long','geo_zoom');


        /**
        *
        * @var UrlCache
        */
        public $url = null;

        public function __construct($id) {
            parent::__construct($id);
            $cultures = HttpContext::current()->culture()->LanguageList();
            foreach ($cultures as $culture) {
                if ($culture['suffix']==='')                    continue;
                foreach ($this->translateFields as $f) {
                    $this->FieldArray[] = $f . $culture['suffix'];
                }
            }
        }

        protected function OnLoad($success) {
            parent::OnLoad($success);
            if ($success) {
                if ($this->IsCollectionItem) {
                    
                } else {
                    $this->url = new UrlCache($this->ID, 'product');
                    $this->url->Load();

                    $this->setTagsValue('tags');
                    
                    $DB = DatabaseProvider::provide();
                    $rw = $DB->Fetch("SELECT member_id FROM #__items_member WHERE item_id = ".$this->ID);
                            
                    $this->setProp('member_id', intval($rw['member_id']));
                    
                }
            }
        }

        public function InsertFromOv($itemProps, $urlItems, $filterList) {
            $itemProps['tags'] = str_replace('"', '', $itemProps['tags']);
            $itemProps['stars'] = _VARINT($itemProps['stars']);
            
            $itemProps['geo_lat'] = round(floatval($itemProps['geo_lat']),10);
            $itemProps['geo_long'] = round(floatval($itemProps['geo_long']),10);
            $itemProps['geo_zoom'] = round(floatval($itemProps['geo_zoom']),1);
            if ($itemProps['geo_lat']==0 || $itemProps['geo_long']==0 || $itemProps['geo_zoom']==0) {
                $itemProps['geo_lat'] = 'NULL';
                $itemProps['geo_long'] = 'NULL';
                $itemProps['geo_zoom'] = 'NULL';
            }
            
            $ok = parent::InsertFrom($itemProps, $filterList);
            $DB = DatabaseProvider::provide();
            try {
                $this->url = new UrlCache($this->ID, 'product');
                $urlItems['displaytext'] = $itemProps['name'];
                $this->url->Bind($urlItems);
                $this->url->Insert();
                $DB->Query("INSERT INTO #__reviewsagg SET
                        item_id = ".$this->ID.", score=0, qnt=0");
                $DB->Query("INSERT INTO #__items_member SET member_id=".intval($itemProps['member_id']).",item_id=".$this->ID);
                
                $this->updateCats($itemProps);
                
                for($i=0;$i<7;$i++) {
                    $openTime = 'NULL';
                    $closeTime = 'NULL';
                    if (intval($itemProps['workdays'][$i]['enabled'])>0) {
                        if ($closeTime=='00:00') 
                            $closeTime = '23:59';
                        $openTime = "STR_TO_DATE('".$itemProps['workdays'][$i]['opentime'].':00'."','%H:%i:%s')";
                        $closeTime = "STR_TO_DATE('".$itemProps['workdays'][$i]['closetime'].':00'."','%H:%i:%s')";
                    }
                    $DB->Query("INSERT INTO #__items_hours SET 
                                        item_id = ".$this->ID.",
                                        week_day = ".($i+1).",
                                        opentime = ".$openTime.",
                                        closetime = ".$closeTime);
                }
                
            } catch (Exception $ex) {
                parent::Remove();
                if (!empty($this->ID)) {
                    $this->url = new UrlCache($this->ID, 'product');
                    $this->url->Remove();
                    $DB->Query("DELETE FROM #__reviewsagg where item_id = ".$this->ID);
                    $DB->Query("DELETE FROM #__items_member where item_id=".$this->ID);
                    $DB->Query("DELETE FROM #__items_hours where item_id=".$this->ID);
                    $DB->Query("DELETE FROM #__items_cats WHERE item_id=".$this->ID);
                }
                throw $ex;
            }
            return $ok;
        }
        
        private function updateCats($itemProps) {
            $DB = DatabaseProvider::provide();
            $DB->Query("DELETE FROM #__items_cats WHERE item_id=".$this->ID);
            if (array_isset($itemProps['cats'])) {
                $query = "INSERT INTO #__items_cats (item_id,parent_id) VALUES ";
                $isset = false;
                foreach ($itemProps['cats'] as $value) {
                    if (intval($value)<=0)
                        continue;
                    $isset = true;
                    $query = $query."(".$this->ID.",".intval($value)."),";
                }
                if ($isset) {
                    $query = substr($query,0,  strlen($query)-1);
                    $DB->Query($query);
                }
            }
        }


        public function UpdateFromOv($itemProps, $urlItemProps) {
            set_time_limit(0);
            
            $itemProps['tags'] = str_replace('"', '', $itemProps['tags']);
            $itemProps['stars'] = _VARINT($itemProps['stars']);
            $itemProps['geo_lat'] = round(floatval($itemProps['geo_lat']),10);
            $itemProps['geo_long'] = round(floatval($itemProps['geo_long']),10);
            $itemProps['geo_zoom'] = round(floatval($itemProps['geo_zoom']),1);
            if ($itemProps['geo_lat']==0 || $itemProps['geo_long']==0 || $itemProps['geo_zoom']==0) {
                $itemProps['geo_lat'] = 'NULL';
                $itemProps['geo_long'] = 'NULL';
                $itemProps['geo_zoom'] = 'NULL';
            }
            
            
            $ok = parent::UpdateFrom($itemProps);
            
            $DB = DatabaseProvider::provide();
            
            $DB->Query("UPDATE #__items_member SET 
                            member_id = ".intval($itemProps['member_id'])."
                        WHERE item_id = ".intval($this->ID));
            
            $this->url = new UrlCache($this->ID, 'product');
            $urlItemProps['displaytext'] = $itemProps['name'];
            $this->url->Load();
            $this->url->Bind($urlItemProps);
            $this->url->Update();
            
            /*
            $cats = ['444','445','447','448','449'];
            
            $itemsAll = $DB->Fill("SELECT id FROM #__items");
            foreach ($itemsAll as $rw) {
                shuffle($cats);
                $cnt = mt_rand(1, 5);
                $query = "INSERT INTO #__items_cats (item_id,parent_id) VALUES ";
                for ($idx=0;$idx<$cnt;$idx++) {
                    $query = $query."(".$rw['id'].",".intval($cats[$idx])."),";
                }
                $DB->Query(substr($query,0,  strlen($query)-1));
            }
            $itemsAll = $DB->Fill("SELECT id,geo FROM #__items");
            foreach ($itemsAll as $rw) {
                list($lat,$long,$zoom) = explode(';', $rw['geo']);
                $DB->Query("UPDATE #__items SET geo_lat=".round($lat,10).",geo_long=".round($long,10).",geo_zoom=".round($zoom,1)." WHERE id=".$rw['id']);
            }
            */
            $this->updateCats($itemProps);
            
            for($i=0;$i<7;$i++) {
                $openTime = 'NULL';
                $closeTime = 'NULL';
                if (intval($itemProps['workdays'][$i]['enabled'])>0) {
                    $openTime = "STR_TO_DATE('".$itemProps['workdays'][$i]['opentime'].':00'."','%H:%i:%s')";
                    $closeTime = "STR_TO_DATE('".$itemProps['workdays'][$i]['closetime'].':00'."','%H:%i:%s')";
                }
                $DB->Query("UPDATE #__items_hours SET 
                                    opentime = ".$openTime.",
                                    closetime = ".$closeTime."
                            WHERE item_id = ".$this->ID." AND week_day = ".($i+1));
            }

            return $ok;
        }
        
        public function Remove() {
            $folder = Configuration::Load('items_imagefolder');
            if (empty($folder))
                throw new Exception('Upload folder not set');
            $this->url = new UrlCache($this->ID, 'product');
            $this->url->Remove();

            $fileList = new ItemFile(null);
            $fileList->setProp('type', 'items');
            $fileList->setProp('parent_id', $this->ID);
            $fileList->RemoveCollectionDB();
            
            rmdirr(ROOTDIR . $folder . $this->ID . '/');

            $DB = DatabaseProvider::provide();
            $DB->Query("DELETE FROM #__reviewsagg where item_id = ".$this->ID);
            
            $ok = parent::Remove();
            return $ok;
        }
        
        
        public function AttachFiles($files) {
            $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder', 'items_imagemaxsize', 'items_imagethumbs'));

            $folder = $confItems['items_imagefolder'];
            $tmpFolder = $confItems['items_tempfolder'];
            if (empty($folder) || empty($tmpFolder))
                throw new Exception('Upload folder not set');
            $UploadDir = ROOTDIR . $folder . $this->ID . '/';
            $tmpFolder = ROOTDIR . $tmpFolder . '/';
            $targetExt = 'jpg';
            if (!is_dir($UploadDir)) {
                if (mkdir($UploadDir, 0777) === false) { // only root upload folder chmod
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory create failed');
                }
            }

            $DB = DatabaseProvider::provide();
            $result = 'Unknown Error';

            $imageMax = $confItems['items_imagemaxsize'];
            $imageThumb = $confItems['items_imagethumbs'];

            foreach ($files["name"] as $i => $name) {
                $DB->Query("insert into #__files
                        (parent_id, type, filename)
                        values ('" . $this->ID . "', 'items', 'dummy')");
                $FileID = intval($DB->LastID());
                $fileParts = array();
                $fileParts['fullpath'] = 'dummy';
                $fileParts['temppath'] = 'dummy';
                $fileParts['thumbpath'] = 'dummy';

                try {
                    if ($files['error'][$i] != UPLOAD_ERR_OK)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file');

                    $uploadedFile = $files["tmp_name"][$i];
                    if (!is_uploaded_file($uploadedFile))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file');

                    $fileManage = new FileManager();

                    if (!$fileManage->isValidFile($uploadedFile, $files['name'][$i], $files['size'][$i], $files['type'][$i]))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Invalid file');

                    $fileParts = $fileManage->ParseAndCleanFileName($files['name'][$i]);
                    if ($fileParts === false)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Invalid file name');

                    $fileParts['name'] = $fileParts['name'] . '_' . $FileID;
                    $fileParts['fullpath'] = $UploadDir . $fileParts['name'] . '.' . $targetExt; // . $fileParts['ext'];
                    $fileParts['temppath'] = $tmpFolder . $fileParts['name'] . '.' . $fileParts['ext'];
                    $fileParts['thumbpath'] = $UploadDir . $fileParts['name'] . '_thumb' . '.' . $targetExt; // . $fileParts['ext'];

                    if (file_exists($fileParts['fullpath']))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'file already exists');

                    if (!move_uploaded_file($uploadedFile, $fileParts['temppath']))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error moving file');

                    $this->ProcessImage($fileParts, $imageMax, $imageThumb);

                    $fleNameRaw = $folder . $this->ID . '/' . $fileParts['name'] . '.' . $targetExt;
                    $thumbNameRaw = $folder . $this->ID . '/' . $fileParts['name'] . '_thumb' . '.' . $targetExt;
                    $fleName = $DB->EscapeValue($fleNameRaw);
                    $thumbname = $DB->EscapeValue($thumbNameRaw);

                    $row['tw'] = 0;
                    $row['th'] = 0;
                    $row['w'] = 0;
                    $row['h'] = 0;
                    if (!empty($thumbNameRaw) && file_exists(ROOTDIR . $thumbNameRaw)) {
                        list($row['tw'], $row['th']) = getimagesize(ROOTDIR . $thumbNameRaw);
                    }
                    if (!empty($fleNameRaw) && file_exists(ROOTDIR . $fleNameRaw)) {
                        list($row['w'], $row['h']) = getimagesize(ROOTDIR . $fleNameRaw);
                    }

                    $DB->Query("UPDATE #__files SET filename=" . $fleName . ",thumbname=" . $thumbname . ",img_width=" . $row['w'] . ", img_height=" . $row['h'] . ", tmb_width=" . $row['tw'] . ", tmb_height=" . $row['th'] . " WHERE id=" . $FileID);

                    $result = array();
                    $result['status'] = "ok";
                    $result['filename'] = $folder . $this->ID . '/' . $fileParts['name'] . '.' . $targetExt;
                    $result['thumbname'] = $folder . $this->ID . '/' . $fileParts['name'] . '_thumb' . '.' . $targetExt;
                    $result['id'] = $FileID;
                    $result = SerilizeToJson($result);
                    if (file_exists($fileParts['temppath']))
                        @unlink($fileParts['temppath']);
                } catch (Exception $ex) {
                    $DB->Query("delete from #__files WHERE id=" . $FileID);
                    if (file_exists($fileParts['temppath']))
                        @unlink($fileParts['temppath']);
                    if (file_exists($fileParts['fullpath']))
                        @unlink($fileParts['fullpath']);
                    if (file_exists($fileParts['thumbpath']))
                        @unlink($fileParts['thumbpath']);
                    throw $ex;
                }
            }
            return $result;
        }

        
        
        
        
        private function getPropsValue($propName,$values) {
            $value = 0;
            if (!is_array($values)) {
                return _VARINT($values);
            }
            $list = Product::getProps($propName, 0);
            $props = $list['items'];
            foreach ($values as $propID) {
                if (array_key_exists(_VARINT($propID), $props)) {
                    $value = $value | _VARINT($props[_VARINT($propID)]['itemvalue']);
                }
            }
            return $value;
        }

        
        
        private function ProcessImage($fileParts, $maxProps, $thumbProps) {
            $imgManage = new ImageManager($fileParts['temppath']);
            $mem = $imgManage->resizeImage(intval($maxProps['w']), intval($maxProps['h']), $maxProps['mode'], true);
            if (!$mem)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');
            $ok = $imgManage->saveImage($fileParts['fullpath'], 100);
            if (!$ok)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');

            $imgManage = new ImageManager($fileParts['temppath']);
            $mem = $imgManage->resizeImage($thumbProps['w'], $thumbProps['h'], $thumbProps['mode']);
            if (!$mem)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
            $ok = $imgManage->saveImage($fileParts['thumbpath'], 100);
            if (!$ok)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');

            return true;
        }

        public function getFiles() {
            $ItemsTemplate = new ItemFile(null);
            $ItemsTemplate->setProp('parent_id', $this->ID);
            $ItemsTemplate->setProp('type', 'items');

            $ItemCollection = $ItemsTemplate->Collection();
            $ItemCollection->ordering = 'isdefault DESC,id ASC';
            $ItemCollection->Load(false, 0, 0, false);
            $totalRows = $ItemCollection->totalCount(false);
            return array("records" => $totalRows, "rows" => $ItemCollection->toArray());
        }
        
        public function getHours() {
            $DB = DatabaseProvider::provide();
            $DB->Query("SELECT week_day, DATE_FORMAT(opentime,'%H:%i') as opentime, DATE_FORMAT(closetime,'%H:%i') as closetime FROM #__items_hours WHERE item_id=".intval($this->ID)." ORDER BY week_day");
            $ret = array();
            if ($DB->RowCount()>0) {
                while ($row = $DB->ReadRow()) {
                    $row['checked'] = empty($row['opentime'])?'':'checked="checked"';
                    $ret[intval($row['week_day'])-1] = $row;
                }
            }
            return $ret;
        }

        public function getJsonCollection($pageSize, $startIndex, $pageNum, $orderby) {
            if ($pageNum <= 0)
                throw new EPageError(EPageError::CUSTOM_ERROR, "page number is wrong");

            $pid = intval($this->getProp('parent_id'));
            $where = ' WHERE 1=1 ';
            if ($pid >0) {
                $where = ' INNER JOIN #__items_cats tcc ON tcc.item_id = ti.id WHERE tcc.parent_id=' . $pid;
            }
            

            $DB = DatabaseProvider::provide();
            /*
            $poses = $DB->Fetch('SELECT max(pos) as maxpos,min(pos) as minpos FROM #__items ti ' . $where);
            if ($poses == false)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'failed find positions in collection');

            $maxpos = intval($poses['maxpos']);
            $minpos = intval($poses['minpos']);
            */
            $maxpos = 0;
            $minpos = 0;

            $ok = $DB->Query("SELECT ti.id, ti.parent_id, ti.manufact, ti.name, ti.pos, ti.visible
                                FROM #__items ti
                                INNER JOIN #__urlcache tu ON (tu.parent_id=ti.id AND tu.componenttype='product') 
                                " . $where . "
                                ORDER BY " . (empty($orderby) ? 'pos' : $orderby) . "
                                LIMIT " . $startIndex . ($pageSize == 0 ? "" : "," . $pageSize));

            $items = array();
            if ($DB->RowCount() > 0) {
                while ($row = $DB->ReadRow()) {
                    $visible = _VARINT($row['visible']) == 1 ? true : false;
                    $row['notvisible'] = ($visible ? '0' : '1');
                    $row['visiblemark'] = ($visible ? '' : 'n');
                    $row['showup'] = (_VARINT($row['pos']) > $minpos ? '1' : '0');
                    $row['showdown'] = (_VARINT($row['pos']) < $maxpos ? '1' : '0');
                    $row['maxpos'] = $maxpos;
                    $row['minpos'] = $minpos;
                    $items[] = $row;
                }
            }
            $totalRows = $DB->Scalar("SELECT count(id) FROM #__items ti " . $where);
            $arr = array("page" => $pageNum, "records" => $totalRows, "rows" => $items);
            return SerilizeToJson($arr);
        }

        /*
        public function getGeo() {
            $ret = array(
                'geoexists' => ''
            );
            $geo = $this->getProp('geo');
            if (!empty($geo)) {
                list($latstr, $longstr, $zoomstr, $imageStr) = explode(';', $geo, 4);
                $lat = floatval($latstr);
                $long = floatval($longstr);
                $zoom = floatval($zoomstr);
                if ($lat != 0 || $long != 0) {
                    if ($zoom === 0) {
                        $zoom = 12;
                    }
                    $ret = array(
                        'geoexists' => 'true',
                        'lat' => $lat,
                        'long' => $long,
                        'zoom' => $zoom,
                        'image' => $imageStr
                    );
                }
            }
            return $ret;
        }
        */
        
        /*
        public function getBookings() {
            $DB = DatabaseProvider::provide();
            
            $qry = new QueryBuilder();
            $qry->Select(array('id', 'user_id', 'item_id', 'date_created_~', 'date_from_$', 'date_to_$', 'persons', 'servicelist'), '#__bookings')
                    ->Where(' item_id = '.  _VARINT($this->ID)." AND confirmed>".Booking::TYPE_UNPAYED);
            
            $dates = array();
            $DB->Query($qry->GetQuery());
            while ($row = $DB->ReadRow()) {
                $dates[] = array('date_from'=>$row['date_from'],'date_to'=>$row['date_to']);
            }
            return $dates;
        }
         * 
         */
        
        // petq chi
        public function isAvilible($startDateStr,$endDateStr) {
            $DB = DatabaseProvider::provide();
            $bookCount = $DB->Scalar("SELECT COUNT(1) 
                            FROM #__bookings tb 
                            WHERE tb.item_id = "._VARINT($this->ID)." AND 
                            tb.date_to > STR_TO_DATE('".$startDateStr."','%d.%m.%Y') AND 
                            tb.date_from < STR_TO_DATE('".$endDateStr."','%d.%m.%Y') AND
                            tb.confirmed>".Booking::TYPE_UNPAYED);
            if ($bookCount===false) {
                throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
            }
            return (_VARINT($bookCount)>0?false:true);
        }
       

        public static function getTags() {
            $DB = DatabaseProvider::provide();
            $tagsMixed = $DB->Fill("SELECT tags FROM #__items WHERE tags<>''");
            $ret = array();
            $ret['tags'] = array();
            
            $tags = '';

            if (!is_array($tagsMixed) || count($tagsMixed) <= 0)
                return $ret;

            foreach ($tagsMixed as $value) {
                if (!empty($value['tags'])) {
                    $list = explode(',', $value['tags']);
                    foreach ($list as $tag) {
                        if (!in_array($tag, $ret['tags'])) {
                            $ret['tags'][] = $tag;
                            $tags = $tags . '<option value="' . $tag . '">' . $tag . '</option>';
                        }
                    }
                }
            }
            $ret['tags'] = $tags;
            return $ret;
        }
        
        public static function getProps() {
            $DB = DatabaseProvider::provide();
            $DB->Query("SELECT id, parent_id, name, itemvalue FROM #__itemprops order by parent_id, pos");
            $ret = array();
            while ($row = $DB->ReadRow()) {
                if (!array_key_exists($row['parent_id'], $ret)) {
                    $ret[$row['parent_id']] = array();
                }
                $row['itemvalue'] = intval($row['itemvalue']);
                $ret[$row['parent_id']][] = $row;
            }
            
            return $ret;
        }
        /*
        public static function getProps($propName,$currentValue) {
            $DB = DatabaseProvider::provide();
            $propsTable = $DB->Fill("SELECT id, name, itemvalue FROM #__itemprops WHERE parent_id='".$propName."' order by pos",'id');
            $ret = array();
            $ret['items'] = $propsTable;
            $ret['html'] = '';
            $ret['values'] = array();
            if (!is_array($propsTable) || count($propsTable) <= 0)
                return $ret;
            $currentValueInt = _VARINT($currentValue);
            foreach ($propsTable as $value) {
                $ret['html'] = $ret['html'] . '<option value="' . $value['id'] . '">' . $value['name'] . '</option>';
                if (($currentValueInt & _VARINT($value['itemvalue']))===_VARINT($value['itemvalue'])) {
                    $ret['values'][] = $value['id'];
                }
            }
            return $ret;
        }
         * 
         */
        public static function getManufactList($start, $lang) {
            $DB = DatabaseProvider::provide();
            $start = $DB->EscapeValue($start, false);
            $start = filterInput($start);
            $suff = '';
            if ($lang === 1) {
                $suff = '_lg1';
            } else if ($lang === 2) {
                $suff = '_lg2';
            }
            $query = "SELECT distinct ti.manufact" . $suff . " as name
                        FROM #__items ti
                        where ti.manufact" . $suff . " LIKE '" . $start . "%'";
            $ok = $DB->Query($query);
            if ($ok === false)
                return array();

            $arr = $DB->ReadAll();
            return SerilizeToJson($arr);
        }
        public static function getCitiesList($start, $lang) {
            $DB = DatabaseProvider::provide();
            $start = $DB->EscapeValue($start, false);
            $start = filterInput($start);
            $suff = '';
            if ($lang === 1) {
                $suff = '_lg1';
            } else if ($lang === 2) {
                $suff = '_lg2';
            }
            $query = "SELECT distinct ti.city" . $suff . " as name
                        FROM #__items ti
                        where ti.city" . $suff . " LIKE '" . $start . "%'";
            $ok = $DB->Query($query);
            if ($ok === false)
                return array();

            $arr = $DB->ReadAll();
            return SerilizeToJson($arr);
        }
        
        public static function getNames($start) {
            $DB = DatabaseProvider::provide();
            $start = $DB->EscapeValue($start, false);
            $start = filterInput($start);
            $query = "SELECT ti.id,CONCAT(ti.name,' (',ifnull(ti.city,''),' )') as name
                        FROM #__items ti ORDER BY ti.parent_id,ti.pos";
            $ok = $DB->Query($query);
            if ($ok === false)
                return array();

            $arr = $DB->ReadAll();
            return SerilizeToJson($arr);
        }
        
        /* helpers */

        private function setTagsValue($tagsName) {
            $tags = $this->getProp($tagsName);
            if (!empty($tags)) {
                $tagsArr = '"' . mb_str_replace(',', '","', $tags) . '"';
                $this->setProp($tagsName . 'value', $tagsArr);
            }
        }
        
        
        public static function getBookings($itemProps, $pageSize, $startIndex, $pageNum, $orderby) {
            $DB = DatabaseProvider::provide();
            $wheres = array();
            
            if (empty($orderby)) {
                $orderby = 'tb.id DESC';
            }
            
            if (intval($itemProps['item_id'])>0) {
                $wheres[] = "tb.item_id=".intval($itemProps['item_id']);
            }
            if (intval($itemProps['id'])>0) {
                $wheres[] = "tb.id=".intval($itemProps['id']);
            }
            if (intval($itemProps['member_id'])>0) {
                $wheres[] = "tb.member_id=".intval($itemProps['member_id']);
            }
            if (intval($itemProps['status'])>0) {
                $wheres[] = intval($itemProps['status'])." & tb.status = tb.status";
            }
            if (!empty($itemProps['checkin_start']) || !empty($itemProps['checkin_end'])) {
                $dateCreated_start = ParseDate($itemProps['checkin_start']);
                $dateCreated_end =  ParseDate($itemProps['checkin_end']);
                
                if ($dateCreated_start!==null && $dateCreated_end!==null) {
                    $wheres[] = "tb.checkin BETWEEN STR_TO_DATE(".$DB->EscapeValue($itemProps['checkin_start'].' 00:00:00').",'%d.%m.%Y %H:%i:%s') AND
                                            STR_TO_DATE(".$DB->EscapeValue($itemProps['checkin_end'].' 23:59:59').",'%d.%m.%Y %H:%i:%s')";
                } else if ($dateCreated_start===null && $dateCreated_end!==null) {
                    $wheres[] = "tb.checkin <= STR_TO_DATE(".$DB->EscapeValue($itemProps['checkin_end'].' 23:59:59').",'%d.%m.%Y %H:%i:%s')";
                } else if ($dateCreated_start!==null && $dateCreated_end===null) {
                    $wheres[] = "tb.checkin >= STR_TO_DATE(".$DB->EscapeValue($itemProps['checkin_start'].' 00:00:00').",'%d.%m.%Y %H:%i:%s')";
                }
            }
            
            $res = array(
                'rows' =>array(),
                'records' => 0,
                'page' => $pageNum,
                'onPage' => $pageSize,
                'skip'=> $startIndex
            );
            if (count($wheres)<=0) {
                $wheres[] = "1=1";
            }

            
            $fields = Array('tb.id', 'tb.item_id','ti.name as item_name','ti.geo as item_geo','ti.adres as item_address' , 'tb.member_id','tm.name as member_name','tm.m_phone as member_phone', 'tb.created_~','tb.checkin_~',"DATE_FORMAT(checkin,'%d.%m.%Y') as checkin_date","DATE_FORMAT(checkin,'%H:%i') as checkin_time",'tb.guests','tb.status','tb.descr','CASE WHEN tb.checkin<=NOW() THEN 1 ELSE 0 END AS lating');

            $query = new QueryBuilder();
            $query->Select($fields, "#__bookings tb")
                    ->Join("#__items ti", "ti.id = tb.item_id",'INNER')
                    ->Join("#__members tm", "tm.id = tb.member_id",'INNER');
            $query->Where(implode(' AND ', $wheres))
                ->OrderBy($orderby);

            if ($pageSize>0) {
                $query->Limit($startIndex, $pageSize);
            }

            $sql = $query->GetQuery();

            $DB->Query($sql);
            if ($DB->RowCount()>0) {
                while ($row = $DB->ReadRow()) {
                    $row['book_id'] = intval($row['id']);
                    $row['item_id'] = intval($row['item_id']);
                    $row['guests'] = intval($row['guests']);
                    $row['status'] = intval($row['status']);
                    $row['member_id'] = intval($row['member_id']);
                    $res['rows'][] = $row;
                }
            }

            $res['records'] =intval($DB->Scalar("SELECT count(1) FROM #__bookings tb WHERE ".implode(' AND ', $wheres)));
            return $res;
        }
        
        public static function saveBook($itemProps) {
            $id = intval($itemProps['book_id']);
            
            $checkIn = $itemProps['checkin'];
            $guests = intval($itemProps['guests']);
            
            $checkInDate = DateTime::createFromFormat('d.m.Y H:i:s', $checkIn);
            $now = new DateTime("now");
            if (empty($checkIn) || !($checkInDate && $checkInDate->format('d.m.Y H:i:s') == $checkIn)) {
                throw new EInvalidInputError(array('book-date'));
            }
            
            if ($guests<=0 || $guests>9999) {
                throw new EInvalidInputError(array('book-guests'));
            }
            
            $status = intval($itemProps['status']);
            if ($status<=0 || $status>64) {
                throw new EInvalidInputError(array('book-status'));
            }
            
            $cname =trim(htmlspecialchars($itemProps['member_name']));
            $cphone =trim(htmlspecialchars($itemProps['member_phone']));
            
            $memberID = intval($itemProps['member_id']);
            
            $itemID = intval($itemProps['item_id']);
            
            $pitem = new Product($itemID);
            $pitem->Load();
            
            $DB = DatabaseProvider::provide();
            if ($id>0) {
                $DB->Query("UPDATE #__bookings SET 
                        checkin = STR_TO_DATE(".$DB->EscapeValue($checkIn).",'%d.%m.%Y %H:%i:%s'),
                        guests = ".$guests.",
                        status = ".$status."
                        WHERE id = ".$id);
                $DB->Query("INSERT INTO #__bookings_logs SET 
                                   book_id = ".$id.",
                                   member_id = ".$memberID.",
                                   checkin = STR_TO_DATE(".$DB->EscapeValue($checkIn).",'%d.%m.%Y %H:%i:%s'),
                                   guests = ".$guests.",    
                                   admin_id = ".intval(HttpContext::current()->Identity()->ID).",
                                   status = ".$status);
            } else {
                $DB->Query("INSERT INTO #__bookings SET 
                        item_id = ".$itemID.",
                        member_id = 0,    
                        checkin = STR_TO_DATE(".$DB->EscapeValue($checkIn).",'%d.%m.%Y %H:%i:%s'),
                        guests = ".$guests.",
                        status = ".$status.",
                        c_name = ".$DB->EscapeValue($cname).",
                        c_phone = ".$DB->EscapeValue($cphone));
                $id = intval($DB->LastID());
                $DB->Query("INSERT INTO #__bookings_logs SET 
                                   book_id = ".$id.",
                                   member_id = 0,
                                   checkin = STR_TO_DATE(".$DB->EscapeValue($checkIn).",'%d.%m.%Y %H:%i:%s'),
                                   guests = ".$guests.",    
                                   admin_id = ".intval(HttpContext::current()->Identity()->ID).",
                                   status = ".$status.",
                                   c_name = ".$DB->EscapeValue($cname).",
                                   c_phone = ".$DB->EscapeValue($cphone));
            }
        }
        
        public function getCats() {
            $DB = DatabaseProvider::provide();
            $DB->Query("SELECT parent_id FROM #__items_cats WHERE item_id=".$this->ID);
            $tbl = array();
            while ($row = $DB->ReadRow()) {
                $tbl[] = $row['parent_id'];
            }
            return  $tbl;
        }

    }

?>