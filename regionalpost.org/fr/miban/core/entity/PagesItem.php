<?php
    
    class PagesItem extends TableEditableItem {

        // must override
        
        protected $table = '#__pages';
        protected $FieldArray = Array('id', 'block_id', 'name', 'descr', 'content', 'pos', 'visible', 'date_modified', 'parent_id', 'tags', 'alias','date_object_~','ainfo','binfo','cinfo','tagsb','price','intsa','intsb','intsc');
        protected $CollectionFieldArray = Array('tp.id', 'tp.block_id', 'tp.name', 'tp.pos', 'tp.visible', 'tp.date_modified', 'tp.parent_id','tp.alias','tp.date_object_~');
        protected $ValidationFields = Array('name'=>'!null');
        
        private $translateFields = array('name', 'descr', 'content', 'ainfo', 'binfo', 'cinfo');
        

        /**
         *
         * @var UrlCache
         */
        public $url = null;

        
        public function __construct($id) {
            parent::__construct($id);
            $cultures = HttpContext::current()->culture()->LanguageList();
            foreach ($cultures as $culture) {
                if ($culture['suffix']==='') continue;
                foreach ($this->translateFields as $f) {
                    $this->FieldArray[] = $f . $culture['suffix'];
                }
            }
        }
       
        protected function OnLoad($success) {
            parent::OnLoad($success);
            if ($success) {
                if ($this->IsCollectionItem) {
                    // $this->setProp('pageurl', 'HTTP://'.$_SERVER['HTTP_HOST'].'/'.$this->getProp('alias').$this->getProp('linktype'));
                } else {
                    $this->url = new UrlCache($this->ID,'pages');
                    $this->url->Load(false);
                    if ($this->url->ID<=0) {
                        $this->url = null;
                    }
                }
            }
        }
        
        public function getCollectionArray($pageSize,$startIndex,$pageNum,$orderby,$params) {
            if ($pageNum<=0)                
                throw new EPageError(EPageError::CUSTOM_ERROR,"page number is wrong");
            
            $qry = new QueryBuilder();
            $where = '';
            $pps = $this->Props;
            unset($pps['pid']);
            
            if (isset($pps) && is_array($pps) && count($pps)>0) {
                $where =' WHERE '.$qry->BuildConditions($pps);
            } 
            
            $DB = DatabaseProvider::provide();
            $poses = $DB->Fetch('SELECT count(1) as totalrows,max(pos) as maxpos,min(pos) as minpos FROM '.$this->table.$where);
            if ($poses==false) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'failed find positions in collection');
            
            $fields = $this->CollectionFieldArray;
            $fields[] = intval($poses['maxpos']).' as maxpos';
            $fields[] = intval($poses['minpos']).' as minpos';
            $fields[] = "(SELECT count(1) FROM ".$this->table." ti WHERE ti.parent_id=tp.id) as childcount";
            
            $qry->Select($fields, $this->table." tp");
            $pid = _VARINT($this->getProp('pid'));
            if ($pid>0) {
                $qry->Join("#__items_cats tc", "tc.item_id = tp.id");
                $where = $where . ' AND tc.parent_id = ' .$pid;
            }
            
            $qry->Where($where,'');      
            $qry->OrderBy(empty($orderby)?'tp.pos':$orderby);
            if ($pageSize>0) {
                $qry->Limit($startIndex, $pageSize);
            }
            $arr = array( "page" => $pageNum,"records" => intval($poses['totalrows']), "rows" => array());
            
            if (count($params)>0) {
                $DB->Query($qry->GetQuery());
                if ($DB->RowCount()>0) {
                    while ($row = $DB->ReadRow()) {
                        if (array_key_exists($row['id'], $params)) {
                            $row['childblock'] = $params[$row['id']]['block_id'];
                            $row['childallowadd'] = $params[$row['id']]['allowadd'];
                            $row['childallowmove'] = $params[$row['id']]['allowmove'];
                        }
                        if (array_key_exists($row['block_id'], $params)) {
                            $row['childblock'] = $params[$row['block_id']]['block_id'];
                            $row['childallowadd'] = $params[$row['block_id']]['allowadd'];
                            $row['childallowmove'] = $params[$row['block_id']]['allowmove'];
                        }
                        $arr['rows'][] = $row;
                    }
                }
            } else {
                $arr['rows'] = $DB->Fill($qry->GetQuery());
            }
            
            return $arr;
        }
        
        public function UpdateFromOv($itemProps,$urlItemProps) {
            unset($itemProps['alias']);
            if (empty($itemProps['date_object'])) {
                $itemProps['date_object'] = date('d.m.Y H:i:s');
            }
            $itemProps['price'] = floatval($itemProps['price']);        
            $itemProps['intsa'] = intval($itemProps['intsa']);        
            $itemProps['intsb'] = intval($itemProps['intsb']);        
            $itemProps['intsc'] = intval($itemProps['intsc']);        
            
            
            
            $ok = parent::UpdateFrom($itemProps);
            
            $this->updateCats($itemProps);
            
            if ($this->url!==null) {
                if (is_array($urlItemProps) && array_key_exists('url', $urlItemProps)) {
                    $urlItemProps['displaytext'] = $itemProps['name'];
                    $urlItemProps['meta_robots'] = 1;
                    $this->url->Bind($urlItemProps);
                    $this->url->Update();
                } else {
                    $this->url->UpdateMetas($urlItemProps);
                }
            }    
            return $ok;
        }
        
        public function InsertFromOv($itemProps,$urlItems,$filterList=null) {
            unset($itemProps['alias']);
            if (empty($itemProps['date_object'])) {
                $itemProps['date_object'] = date('d.m.Y H:i:s');
            }
            $itemProps['price'] = floatval($itemProps['price']);        
            $itemProps['intsa'] = intval($itemProps['intsa']);        
            $itemProps['intsb'] = intval($itemProps['intsb']);        
            $itemProps['intsc'] = intval($itemProps['intsc']);        
            
           
            $ok = parent::InsertFrom($itemProps, $filterList);
            $this->updateCats($itemProps);
            
            if (is_array($urlItems) && array_key_exists('url', $urlItems)) {
                try {
                    $this->url = new UrlCache($this->ID,'pages');
                    $urlItems['displaytext'] = $itemProps['name'];
                    $urlItemProps['meta_robots'] = 1;
                    $this->url->Bind($urlItems);
                    $this->url->Insert();
                    
                } catch (Exception $ex) {
                    parent::Remove(); 
                    if (!empty($this->ID)) {
                        $this->url = new UrlCache($this->ID,'pages');
                        $this->url->Remove();
                    }
                    throw $ex;
                }
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
        
        public function Remove() {
            $this->url = new UrlCache($this->ID,'pages');
            $this->url->Remove();
            $ok = parent::Remove();
            return $ok;
        }
        
        
        public function hasChildren() {
            $DB = DatabaseProvider::provide();
            return intval($DB->Scalar("SELECT count(*) FROM #__pages WHERE parent_id=".intval($this->ID)))>0;
        }


        public function parseSpecial() {
            
        }

        public static function getBlock($block_id) {
            $DB=  DatabaseProvider::provide();
            return $DB->Fill("select id,name FROM #__pages WHERE block_id='".$block_id."' ORDER BY name");
        }

        public function AttachFiles($files,$imageMax,$imageThumb,$groupName,$toMaxSize = false) {
            
            
            $confItems = Configuration::BulkLoad(array('items_pagefolder', 'items_tempfolder'));

            $folder = $confItems['items_pagefolder'];
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

            foreach ($files["name"] as $i => $name) {
                $DB->Query("insert into #__files
                        (parent_id, type, filename)
                        values ('" . $this->ID . "', 'pages-".$groupName."', 'dummy')");
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
                    $targetExt = $fileParts['ext'];
                    
                    
                    $fileParts['name'] = $fileParts['name'] . '_' . $FileID;
                    $fileParts['fullpath'] = $UploadDir . $fileParts['name'] . '.' . $targetExt; // . $fileParts['ext'];
                    $fileParts['temppath'] = $tmpFolder . $fileParts['name'] . '.' . $fileParts['ext'];
                    $fileParts['thumbpath'] = $UploadDir . $fileParts['name'] . '_thumb' . '.' . $targetExt; // . $fileParts['ext'];

                    if (file_exists($fileParts['fullpath']))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'file already exists');

                    if (!move_uploaded_file($uploadedFile, $fileParts['temppath']))
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error moving file');

                    $this->ProcessImage($fileParts, $imageMax, $imageThumb,$toMaxSize);

                    $fleNameRaw = $folder . $this->ID . '/' . $fileParts['name'] . '.' . $targetExt;
                    $thumbNameRaw = $folder . $this->ID . '/' . $fileParts['name'] . '_thumb' . '.' . $targetExt;
                    $fleName = $DB->EscapeValue($fleNameRaw);
                    $thumbname = $DB->EscapeValue($thumbNameRaw);

                    $row['tw'] = 0;
                    $row['th'] = 0;
                    $row['w'] = 0;
                    $row['h'] = 0;
                    if (array_isset($imageThumb)) {
                        if (!empty($thumbNameRaw) && file_exists(ROOTDIR . $thumbNameRaw)) {
                            list($row['tw'], $row['th']) = getimagesize(ROOTDIR . $thumbNameRaw);
                        }
                    }
                    if (!empty($fleNameRaw) && file_exists(ROOTDIR . $fleNameRaw)) {
                        list($row['w'], $row['h']) = getimagesize(ROOTDIR . $fleNameRaw);
                    }

                    if (array_isset($imageThumb)) {
                        $DB->Query("UPDATE #__files SET filename=" . $fleName . ",thumbname=" . $thumbname . ",img_width=" . $row['w'] . ", img_height=" . $row['h'] . ", tmb_width=" . $row['tw'] . ", tmb_height=" . $row['th'] . " WHERE id=" . $FileID);
                    } else {
                        $DB->Query("UPDATE #__files SET filename=" . $fleName . ",img_width=" . $row['w'] . ", img_height=" . $row['h'] . " WHERE id=" . $FileID);   
                    }
                    

                    $result = array();
                    $result['status'] = "ok";
                    $result['filename'] = $folder . $this->ID . '/' . $fileParts['name'] . '.' . $targetExt;
                    if (array_isset($imageThumb)) {
                        $result['thumbname'] = $folder . $this->ID . '/' . $fileParts['name'] . '_thumb' . '.' . $targetExt;
                    }
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

        private function ProcessImage($fileParts, $maxProps, $thumbProps,$toMaxSize) {
            $imgManage = new ImageManager($fileParts['temppath']);
            $mem = $imgManage->resizeImage(intval($maxProps['w']), intval($maxProps['h']), $maxProps['mode'], $toMaxSize);
            if (!$mem)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');
            $ok = $imgManage->saveImage($fileParts['fullpath'], 100);
            if (!$ok)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');

            if (array_isset($thumbProps)) {
                $imgManage = new ImageManager($fileParts['temppath']);
                $mem = $imgManage->resizeImage($thumbProps['w'], $thumbProps['h'], $thumbProps['mode']);
                if (!$mem)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
                $ok = $imgManage->saveImage($fileParts['thumbpath'], 100);
                if (!$ok)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
            }
            return true;
        }

        public static function getBlockforMenu($blockID,$parent_id) {
            $DB=  DatabaseProvider::provide();
            return $DB->Fill("SELECT id,name,name_lg1,name_lg2 FROM #__pages WHERE block_id='".$blockID."' ".(intval($parent_id)>0?'AND parent_id='.intval($parent_id):'')." ORDER by pos");
        }
        
        public static function getTags() {
            $DB = DatabaseProvider::provide();
            $tagsMixed = $DB->Fill("SELECT tags FROM #__pages WHERE block_id='imageitems' AND tags<>''");
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
        
        
        public function getCats() {
            $DB = DatabaseProvider::provide();
            $DB->Query("SELECT parent_id FROM #__items_cats WHERE item_id=".$this->ID);
            $tbl = array();
            while ($row = $DB->ReadRow()) {
                $tbl[] = $row['parent_id'];
            }
            return  $tbl;
        }
        
        
        /*
         * public function AttachFiles($files,$imageMax,$imageThumb,$toMaxSize = false) {
            
            
            $confItems = Configuration::BulkLoad(array('items_galeryfolder', 'items_tempfolder', 'galery_imagemaxsize', 'galery_imagethumbs'));

            $folder = $confItems['items_galeryfolder'];
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

            $imageMax = $confItems['galery_imagemaxsize'];
            $imageThumb = $confItems['galery_imagethumbs'];

            foreach ($files["name"] as $i => $name) {
                $DB->Query("insert into #__files
                        (parent_id, type, filename)
                        values ('" . $this->ID . "', 'galery', 'dummy')");
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
                    $targetExt = $fileParts['ext'];
                    
                    
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
         */
    }

?>
