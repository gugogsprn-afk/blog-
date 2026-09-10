<?php

    // changed
    class productWebPage extends MasterWebPage {

     
        protected $Styles = array('css/product.css','js/plugins/timepicker/jquery-clockpicker.min.css');    
        protected $pageParams = array("show", "parent_id");
        
        protected $publicMethods = array('Index','Form','Edit','Add','Move','Visible','Delete','Detach','Favorite','Manufacts','Cities','Files','Attach','Names','Detachimage','Bookings','Savebook');
        
        
        public function Index() {
            //$this->generateFakeItems(_REQINT('parent_id'));
            //$this->fillImageDimensions();
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $orderby = $_REQUEST['sort'][0]['field'] . ' ' . $_REQUEST['sort'][0]['dir'];
            }
            $ItemsTemplate = new Product(null);
            $ItemsTemplate->setProp('parent_id', _REQINT('parent_id'));
            $this->contents = $ItemsTemplate->getJsonCollection(_REQINT('pageSize'), _REQINT('skip'), _REQINT('page'), $orderby);
        }
        
        
        public function Bookings() {
            if (HttpContext::current()->request()->IsJson()) {
                $orderby = '';
                if (isset($_REQUEST['sort'][0]['field'])) {
                    $orderby = $_REQUEST['sort'][0]['field'] . ' ' . $_REQUEST['sort'][0]['dir'];
                }
                
                $this->contents = SerilizeToJson(Product::getBookings($_GET['filters'], _REQINT('pageSize'), _REQINT('skip'), _REQINT('page'), $orderby));
                return;
            }
            $tpl = new Template('bookings/main.inc');
            $tpl->vars['current']['pageparams'] =$this->pageUrl;
            $tpl->vars['current']['appname']=  _APPNAME();
            $tpl->vars['current']['queryparams'] = $this->QueryParams;
            $times = array();
            for($i=0;$i<24;$i++) {
                $times[] = str_pad($i, 2, "0", STR_PAD_LEFT).':00';
                $times[] = str_pad($i, 2, "0", STR_PAD_LEFT).':30';
            }
            $tpl->vars['times'] = SerilizeToJson($times);
            $this->contents = $tpl->Render();
            
        }
        
        public function Savebook() {
            Product::saveBook($_POST['item']);
            $this->contents = true;
        }

        

        public function Form() {
            $method = _REQUEST('act');
            $tpl = new Template('product/form.inc');

            $id = _REQINT('id');
            $pid = _REQINT('parent_id');
            
            
            if ($method === 'sedit') {
                $item = new Product($id);
                $item->Load();
                $tpl->vars['item'] = $item->toArray();
                $pid = _VARINT($item->getProp('parent_id'));
                $tpl->vars['url'] = $item->url->toArray();
                // $tpl->vars['location'] = $item->getGeo();
                
                $file = ItemFile::loadFrom($item->ID,'items-main');
                if ($file!==null) {
                    $tpl->vars['file'] = $file->toArray();
                }
                $tpl->vars['wh'] = $item->getHours();
                $tpl->vars['starsoffchecked'] = _VARINT($item->getProp('starsoff'))===1?'checked="checked"':'';
                $tpl->vars['itemcats'] = SerilizeToJson($item->getCats());
                //$tpl->vars['bookings'] = $item->getBookings();
                $method = 'edit';
            } else {
                $method = 'add';
                $tpl->vars['itemcats'] = '[]';
            }

            $tpl->vars['tags'] = Product::getTags();
            $tpl->vars['props'] = SerilizeToJson(Product::getProps());
            $tpl->vars['ps'] = ItemMenu::getNodes(0);
            /*
            $adProds = new AdProduct(null);
            $tpl->vars['adprods'] = $adProds->getJsonCollection(0, 0, 1, '');
            
            $parent_item = new Category($pid);
            $parent_item->Load();
            $tpl->vars['parent'] = $parent_item->toArray();
            */
            
            $catsTpl = new Category(null);
            $catsCol = $catsTpl->Collection(true);
            $tpl->vars['cats'] = SerilizeToJson($catsCol->Load());

            $img = Configuration::BulkLoad(array('items_imagemaxsize', 'items_imagethumbs','items_logosize','items_slidesize'));
            $tpl->vars['imagesize'] = $img['items_imagemaxsize']['w'] . 'x' . $img['items_imagemaxsize']['h'];
            $tpl->vars['previmagesize'] = $img['items_imagethumbs']['w'] . 'x' . $img['items_imagethumbs']['h'];
            $tpl->vars['imagelogo'] = $img['items_logosize']['w'] . 'x' . $img['items_logosize']['h'];
            $tpl->vars['imageslide'] = $img['items_slidesize']['w'] . 'x' . $img['items_slidesize']['h'];
            $tpl->vars['act'] = $method;
            $tpl->vars['current']['pageparams'] = $this->pageUrl;
            $tpl->vars['current']['appname'] = _APPNAME();
            $tpl->vars['current']['queryparams'] = $this->QueryParams;
            $this->contents = $tpl->Render();
        }

        public function Edit() {
            $item = new Product(_POSTINT('id'));
            $ok = $item->UpdateFromOv(_POST('item'), _POST('url'));
            $postResult = $this->PageEdited($item);
            $params = array();
            $params['result']='ok';
            $params['data'] = $postResult;
            $this->contents = SerilizeToJson($params);

        }

        public function Add() {
            $item = new Product(null);
            $filters = array('parent_id' => _POSTINT('parent_id'));

            $item->InsertFromOv(_POST('item'), _POST('url'), $filters);

            $this->PageAdded($item);
            $params = array();
            $params['result'] = 'ok';
            $params['reload'] = $this->QueryParams;
            $params['reload'][] = array('key' => 'voider', 'value' => 'form');
            $params['reload'][] = array('key' => 'act', 'value' => 'sedit');
            $params['reload'][] = array('key' => 'id', 'value' => $item->ID);
            $this->contents = SerilizeToJson($params);
        }

        public function Move() {
            $item = new Product(_POSTINT('id'));
            $item->Load();
            $filters = array('parent_id' => _VARINT($item->getProp('parent_id')));
            $ok = $item->Move(_REQUEST('direction'), $filters);
            $this->contents = true;
        }

        public function Visible() {
            $page = new Product(_POSTINT('id'));
            $ok = $page->ChangeVisible(_POSTINT('value'));
            $this->contents = true;
        }

        public function Delete() {
            $page = new Product(_POSTINT('id'));
            $ok = $page->Remove();
            $this->contents = true;
        }

        
        public function Detach() {
            $file = new ItemFile(_POSTINT('id'));
            $file->Load();
            $file->Remove();
            $this->contents = true;
        }
        
        
        public function Favorite() {
            $file = new ItemFile(_POSTINT('id'));
            $file->setProp('type', 'items');
            $file->setProp('parent_id', _POSTINT('parent_id'));
            $file->makeDefault();
            $this->contents = true;
        }
        
        public function Manufacts() {
            if (!isset($_POST['filter']) || !isset($_POST['filter']['filters']))
                return "[]";
            $word = $_POST['filter']['filters'][0]['value'];
            $lang = _REQINT('lang');
            $this->contents = Product::getManufactList($word, $lang);
        }
        
        public function Names() {
            $word = $_POST['filter']['filters'][0]['value'];
            $this->contents = Product::getNames($word);
        }
        
        public function Cities() {
            if (!isset($_POST['filter']) || !isset($_POST['filter']['filters']))
                return "[]";
            $word = $_POST['filter']['filters'][0]['value'];
            $lang = _REQINT('lang');
            $this->contents = Product::getCitiesList($word, $lang);
        }
        
        public function Files() {
            $ItemsTemplate = new ItemFile(null);
            $ItemsTemplate->setProp('parent_id', _REQINT('parent_id'));
            $ItemsTemplate->setProp('type', 'items');
            // $ItemsTemplate->setProp('isdefault', 0);
            $this->contents = $ItemsTemplate->getJsonCollection(0, 0, 0, 'isdefault DESC,pos');
        }
        
        public function Attach() {
            $files = $_FILES['files'];
            if (!isset($files['name']))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'file not selected');

            $itemID = _REQINT('id');
            $item = new Product($itemID);
            $item->Load();
            $ok = $item->AttachFiles($files);
            $this->contents = $ok;
        }
        
        public function Detachimage() {
            $fileType = _POST('type');
            $item = new PagesItem(_POSTINT('itemid'));
            $item->Load();

            $file = new ItemFile(_POSTINT('id'));
            $file->Load();

            if (_VARINT($file->getProp('parent_id'))!==_VARINT($item->ID) && $file->getProp('type')!=='items-main')
                throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);

            if ($fileType==='image') {
                $file->Removefile(false);
            } else if ($fileType==='thumb') {
                $file->Removefile(true);
            } else {
                throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
            }
            $this->contents = true;
        }

        
        
        
        
        /**
        * 
        * @param Product $page
        * @return Array
        */
       private function PageAdded($page) {
           $file = null;
           if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
               $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder', 'items_slidesize'));
               $file = ItemFile::CreateEmpty($page->ID, 'items-main');
               $file->handleImageUpload($_FILES['image'], $confItems['items_imagefolder'] . $page->ID . '/', $confItems['items_tempfolder'], ItemFile::UPLOAD_MODE_FILE, $confItems['items_slidesize']);
           }
           if (isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
               $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder', 'items_logosize'));
               if ($file===null) {
                   $file = ItemFile::CreateEmpty($page->ID, 'items-main');
               }
               $file->handleImageUpload($_FILES['thumb'], $confItems['items_imagefolder'] . $page->ID . '/', $confItems['items_tempfolder'], ItemFile::UPLOAD_MODE_THUMB , null, $confItems['items_logosize']);
           }
           $ret = array();
           if ($file!==null) {
               $ret = $file->toArray ();
           }
           return $ret;
       }

       
       /**
        * 
        * @param Product $page
        * @return Array
        */
       private function PageEdited($page) {
           //$this->updateFiles();
           
           $file = null;
           
           if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder', 'items_slidesize'));
                $existsID = ItemFile::FinFileID($page->ID, 'items-main');
                if (_VARINT($existsID) > 0) {
                    $file = new ItemFile($existsID);
                    $file->Load();
                } else {
                    $file = ItemFile::CreateEmpty($page->ID, 'items-main');
                }
                $file->handleImageUpload($_FILES['image'], $confItems['items_imagefolder'] . $page->ID . '/', $confItems['items_tempfolder'], ItemFile::UPLOAD_MODE_FILE, $confItems['items_slidesize']);
           }
           if (isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
               $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder', 'items_logosize'));
               if ($file===null) {
                   $existsID = ItemFile::FinFileID($page->ID, 'items-main');
                    if (_VARINT($existsID) > 0) {
                        $file = new ItemFile($existsID);
                        $file->Load();
                    } else {
                        $file = ItemFile::CreateEmpty($page->ID, 'items-main');
                    }
               }
               $file->handleImageUpload($_FILES['thumb'], $confItems['items_imagefolder'] . $page->ID . '/', $confItems['items_tempfolder'], ItemFile::UPLOAD_MODE_THUMB , null, $confItems['items_logosize']);
           }
           
           $ret = array();
           if ($file!==null) {
               $ret = $file->toArray ();
           }
           return $ret;
       }

        private function updateFiles() {
            $DB = DatabaseProvider::provide();
            /*
            $items=$DB->Fill("SELECT id FROM tb_items");
            foreach ($items as $row) {
                $id = intval($row['id']);
                for($i=0;$i<7;$i++) {
                    $openTime = 'NULL';
                    $closeTime = 'NULL';
                    if ($i==5) {
                        $openTime = "STR_TO_DATE('10:00','%H:%i:%s')";
                        $closeTime = "STR_TO_DATE('22:00','%H:%i:%s')";
                    } else if ($i<5) {
                        $openTime = "STR_TO_DATE('11:00','%H:%i:%s')";
                        $closeTime = "STR_TO_DATE('00:00','%H:%i:%s')";
                    }
                    
                    $DB->Query("INSERT INTO #__items_hours SET 
                                        item_id = ".$id.",
                                        weekday = ".($i+1).",
                                        opentime = ".$openTime.",
                                        closetime = ".$closeTime);
                }
            }
            return;
            */
            
            $items=$DB->Fill("SELECT id FROM tb_items WHERE id<>2131");
            $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_tempfolder'));

            $sourceSlide = ROOTDIR.'tmp/braind-dargett-big-cover.jpg';
            $sourceLogo = ROOTDIR.'tmp/untitled_11x_thumb.jpg';
            
            
            foreach ($items as $row) {
                $id = intval($row['id']);
                $folder = $confItems['items_imagefolder'] . $id . '/';
                if (!is_dir(ROOTDIR.$folder)) {
                    if (mkdir(ROOTDIR.$folder, 0777) === false) { 
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory create failed');
                    }
                }
                $targetSlide = $folder.'braind-dargett-big-cover_'.$id.'.jpg';
                $targetLogo =  $folder.'untitled_11x_thumb_'.$id.'_thumb.jpg';
                copy($sourceSlide,ROOTDIR.$targetSlide);
                copy($sourceLogo,ROOTDIR.$targetLogo);
                
                $DB->Query("INSERT INTO tb_files SET parent_id=".$id.",type='items',isdefault=1,
                        filename=" .$DB->EscapeValue($targetSlide) . ",
                        thumbname=" . $DB->EscapeValue($targetLogo) . ",
                        img_width = " . intval(1920) . ", 
                        img_height = " .intval(500) . ", 
                        tmb_width = " . intval(250) . ", 
                        tmb_height = " . intval(250) . ", 
                        mime_type = 'image/jpeg', 
                        mime_thumb = 'image/jpeg'");
            }
        }






       private function showProductInfo() {
            $id = _POSTINT('id');
            $item = new Product($id);
            $item->Load();
            $files = $item->getFiles();
            $itemArr = $item->toArray();
            $itemArr['files'] = $files;
            $this->contents = SerilizeToJson($itemArr);
        }

        
        
        private function generateFakeItems($parent_id) {
            if ($parent_id==0)
                return;
            
            $titles = array(
                'A&W Restaurants',
                'Americas Incredible Pizza Company',
                'Applebees',
                'Arbys',
                'Arctic Circle Restaurants',
                'Bakers Square',
                'Benihana',
                'Black-eyed Pea',
                'Boston Market',
                'Carrows',
                'Champps Americana',
                'Donatos Pizza',
                'Fatburger',
                'Green Burrito',
                'Hobees Restaurant',
                'Jamba Juice',
                'Johnny Rockets',
                'Montana Mikes',
                'Pizza Ranch',
                'Planet Hollywood',
                'Quaker Steak & Lube',
                'RA Sushi',
                'Redstone American Grill',
                'Seattles Best Coffee',
                'Skyline Chili',
                'Souplantation and Sweet Tomatoes',
                'Sweet Tomatoes',
                'Taco Cabana',
                'Taco Mayo',
                'Umami Burger');
            $_LT = count($titles)-1;
            
            $DB = DatabaseProvider::provide();
            set_time_limit(0);
            $սfolder = ROOTDIR.'tmp/';
            $FilesScan = scandir($սfolder);
            $files = array();
            foreach ($FilesScan as $file) {
                if (is_dir($սfolder . $file))
                    continue;
                $files[] = array(
                    'filename' => $file,
                    'filepath' => $սfolder . $file
                );
            }

            $props = array(
                'propsa'=>array(1,2,4,8,16,32,64,128,256),
                'payments'=>array(1,2,4,8,16),
                'persons'=>array(1,2,4,8,16)
            );
            
            $confItems = Configuration::BulkLoad(array('items_imagefolder', 'items_imagemaxsize', 'items_imagethumbs'));
            $folder = $confItems['items_imagefolder'];
            $imageMax = $confItems['items_imagemaxsize'];
            $imageThumb = $confItems['items_imagethumbs'];

            $totalCount = rand(15, 30);
            for ($i = 1; $i < $totalCount; $i++) {

                $tidx = rand(0,$_LT);
        
                $item = array();
                
                $item['parent_id'] = $parent_id;
                $item['name'] = ucfirst($titles[$tidx]);
                $item['manufact'] = getRandomManufact();
                $item['descr'] = getRandomDescr(20);
                $item['content'] = getRandomDescr(40);
                
                $lat = (rand(400865,402018)/10000).str_pad(rand(0,99999),5,'0',STR_PAD_LEFT ).str_pad(rand(0,99999),5,'0',STR_PAD_LEFT );
                $long = (rand(442780,445829)/10000).str_pad(rand(0,99999),5,'0',STR_PAD_LEFT ).str_pad(rand(0,99999),5,'0',STR_PAD_LEFT );
                $item['geo'] = $lat.';'.$long.';'.rand(5,18).';';
                
                
                $item['adres'] = 'M5, Ptghunk, Հայաստան';
                $item['phone'] = '+374 00 000 000';
                
                foreach ($props as $key=>$pp) {
                    $aa = $pp;
                    shuffle($aa);
                    $PropsCnt = rand(1,count($aa));
                    $vle = 0;
                    for($f = 0;$f<$PropsCnt;$f++) {
                        $vle = $vle | _VARINT($aa[$f]);
                    }
                    $item[$key] = $vle;
                }
                
                
                $item['stars'] = rand(1,5);
                $item['starsoff'] = rand(0,1);
                $item['city'] = getRandomManufact();
                
                /*
                $filters = array('parent_id' => _POSTINT('parent_id'));
                $item->InsertFromOv(_POST('item'), _POST('url'), $filters);
                
                $tagsMixed = array('tags' => getRandomTags(3, 12),'propsa'=>$vle);
                */
                $rnd = rand(10, 10000);
                
                $urlProps = array();
                $urlProps['alias'] = translitWord($item['name']) . '-'.$parent_id.'-'.$i.'-'.$rnd;
                $builded = UrlCache::BuildUrl($urlProps['alias'], 'product', $parent_id,  'add');
                $urlProps['url'] = $builded['url'];
                $urlProps['meta_title'] = $item['name'];   
                $urlProps['componenttype'] = 'product';
                
                $prod = new Product(null);
                $filters = array('parent_id' =>  $parent_id);
                $prod->InsertFromOv($item, $urlProps, $filters);

                // files attach
                $UploadDir = $folder . $prod->ID . '/';

                if (!is_dir( ROOTDIR . $UploadDir)) {
                    mkdir( ROOTDIR . $UploadDir, 4664);
                }
                $id = $prod->ID;
                
                shuffle($files);
                $totalFiles = rand(1, 4);
                for ($j = 0; $j < $totalFiles; $j++) {
                    
                    $imagePath = $files[$j]['filepath'];
                    $fileParts = pathinfo($imagePath);
                    $fileParts['filename'] = str_replace(array('(',')',' '),array('','','_'), $fileParts['filename']);
                    
                    $DB->Query("insert into #__files
                        (parent_id, type, filename)
                        values ('" . $id . "', 'items', 'dummy')");
                    $FileID = intval($DB->LastID());
                    
                    $targetName = $UploadDir.$fileParts['filename'].'_'.$j.'.jpg'; // .$fileParts['extension']
                    $targetThumbName = $UploadDir.$fileParts['filename'].'_'.$j.'_thumb.jpg'; // .$fileParts['extension']
                    
                    $imgManage = new ImageManager($imagePath);
                    $mem = $imgManage->resizeImage(intval($imageMax['w']), intval($imageMax['h']), $imageMax['mode'], true);
                    if (!$mem)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');
                    $ok = $imgManage->saveImage(ROOTDIR .$targetName, 100);
                    if (!$ok)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');

                    $imgManage = new ImageManager($imagePath);
                    $mem = $imgManage->resizeImage($imageThumb['w'], $imageThumb['h'], $imageThumb['mode']);
                    if (!$mem)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
                    $ok = $imgManage->saveImage(ROOTDIR .$targetThumbName, 100);
                    if (!$ok)
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');

                    list($imw, $imh) = getimagesize(ROOTDIR.$targetName);
                    list($tmw, $tmh) = getimagesize(ROOTDIR.$targetThumbName);
                    
                    $fleName = $DB->EscapeValue($targetName);
                    $thumbname = $DB->EscapeValue($targetThumbName);
                    $DB->Query("UPDATE #__files SET filename=" . $fleName . ",
                                                    thumbname=" . $thumbname . ",
                                                    img_width = " . intval($imw) . ", 
                                                    img_height = " .intval($imh) . ", 
                                                    tmb_width = " . intval($tmw) . ", 
                                                    tmb_height = " . intval($tmh) . ", 
                                                    mime_type = 'image/jpg', 
                                                    mime_thumb = 'image/jpg'   
                                        WHERE id=" . $FileID);
                }
            }
        }

        private function fillImageDimensions() {
            set_time_limit(0);
            $DB = DatabaseProvider::provide();
            $imageThumb = Configuration::Load('items_imagethumbs');
            
            $DB->Query("SELECT id,filename,thumbname FROM #__files where type='items'");
            $arr = array();
            while ($row=$DB->ReadRow()) {
                $thumbFile = $row['thumbname'];
                $fullFile = $row['filename'];
                $row['tw'] = $imageThumb['w'];
                $row['th'] = $imageThumb['h'];
                $row['w'] = 0;
                $row['h'] = 0;
                $id =  _VARINT($row['id']);
                if (!empty($fullFile) && file_exists(ROOTDIR.$fullFile)) {
                    list($row['w'], $row['h']) = getimagesize(ROOTDIR.$fullFile);
                }
                if (!empty($thumbFile) && file_exists(ROOTDIR.$thumbFile)) {
                    @unlink(ROOTDIR.$thumbFile);
                }
                
                $imgManage = new ImageManager(ROOTDIR.$fullFile);
                $mem = $imgManage->resizeImage($imageThumb['w'], $imageThumb['h'], $imageThumb['mode']);
                if (!$mem)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
                $ok = $imgManage->saveImage(ROOTDIR .$thumbFile, 100);
                if (!$ok)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to thumb image');
                $row['id'] = $id;
                $arr[] = $row;
            }
            foreach ($arr as $value) {
                $DB->Query('UPDATE #__files SET img_width='.$value['w'].', img_height='.$value['h'].', tmb_width='.$value['tw'].', tmb_height='.$value['th'].' WHERE id='.$value['id']);
            }
        }
        
    }

?>
