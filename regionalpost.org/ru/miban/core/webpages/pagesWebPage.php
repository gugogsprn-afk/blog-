<?php

    class pagesWebPage extends MasterWebPage {
        
    protected $Styles = array('css/pages.css');    
    protected $pageParams =array("show");
    
    private $AllowAdd = array('menu','other','imageitems','publicat','specitems', 'works'); 
    private $AllowMove = array('menu','other','imageitems','publicat', 'specitems', 'works');
    
    
    protected $publicMethods = array('Index','Form','Edit','Add','Move','Visible','Delete','Detach','Detachgal','Files','Attach','Setlink','Names');
    private $orderArr = array('imageitems'=>'{ field: "date_object", dir: "DESC" }');
    private $dateArr = array('imageitems');

    private $imageSettings = array(
        'imageitems'=>array(
            'createthumb'=>false,
            'thumb'=>array('mode'=>'smarty','w'=>220,'h'=>225),
            'image'=>array('mode'=>'smarty','w'=>720,'h'=>485),
            'ext'=>'jpg'
        ),
        "imageitems-galery"=>array(
            'createthumb'=>true,
            'thumb'=>array('mode'=>'smarty','w'=>1200,'h'=>630,'maxsize'=>true),
            'image'=>array('mode'=>'smarty','w'=>1200,'h'=>630,'maxsize'=>true),
            'ext'=>'jpg'
        ),
        'publicat'=>array(
            'createthumb'=>false,
            'thumb'=>array('mode'=>'smarty','w'=>180,'h'=>240),
            'image'=>true,
            'ext'=>'jpg'
        ),
        'specitems'=>array(
            'createthumb'=>false,
            'thumb'=>array('mode'=>'smarty','w'=>220,'h'=>225),
            'image'=>false,
            'ext'=>'jpg'
        ),
        'fixed'=>array(
            'createthumb'=>false,
            'thumb'=>array('mode'=>'smarty','w'=>720,'h'=>485),
            'image'=>false,
            'ext'=>'jpg'
        ),
        'works'=>array(
            'createthumb'=>false,
            'thumb'=>array('mode'=>'smarty','w'=>220,'h'=>225),
            'image'=>array('mode'=>'smarty','w'=>720,'h'=>485),
            'ext'=>'jpg'
        )
    );
    
    
    private $editForms = array(
        'menu'=>'form_menu',
        'fixed'=>'form_fixed',
        'imageitems'=>'form_imageitems',
        'publicat'=>'form_publicat',
        'specitems'=>'form_specitems',
        'works'=>'form_works'
    );

    private $childList = array(
       'specitems' => 'works'
    );


    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $orderby = 'tp.'.$_REQUEST['sort'][0]['field'].' '.$_REQUEST['sort'][0]['dir'];
            }
            $pageItemsTemplate = new PagesItem(null);
            $pageItemsTemplate->setProp('block_id', _REQUEST('block_id'));
            if (isset($_REQUEST['parent_id']) && _REQINT('parent_id')>0) {
                $pageItemsTemplate->removeProp('block_id');
                $pageItemsTemplate->setProp('parent_id', _REQINT('parent_id'));
            }
            
            if (isset($_REQUEST['pid'])) {
                $pageItemsTemplate->setProp('pid', _REQINT('pid'));
            }
            
            $params = array();
            foreach ($this->childList as $key => $value) {
                $params[$key] = array(
                    'block_id'=>$value,
                    'allowadd'=>'false',
                    'allowamove'=>'false'
                );
                if (in_array($value, $this->AllowAdd)) {
                    $params[$key]['allowadd'] = 'true';
                }
                if (in_array($value, $this->AllowMove)) {
                    $params[$key]['allowmove'] = 'true';
                }
            }
            $pagesArr = $pageItemsTemplate->getCollectionArray(_REQINT('pageSize'), _REQINT('skip'), _REQINT('page'), $orderby,$params);
            //$pagesArr['allowadd'] = array_key_exists(_REQUEST('block_id'), $this->AllowAdd);
            //$pagesArr['allowmove'] = array_key_exists(_REQUEST('block_id'), $this->AllowMove);
            $this->contents = SerilizeToJson($pagesArr);
            return;
        }
        $blockID = _REQUEST('block_id');
        $pagesBlock = new PagesBlock($blockID);
        $pagesBlock->Load();
        $parentTitle =$pagesBlock->getProp('displaytext');
        
        /*
        if (_REQINT('parent_id')>0) {
            $subPage = new PagesItem(_REQINT('parent_id'));
            $subPage->Load();
            $parentTitle = 'Pages in '.$subPage->getProp('name');
        }
        */
        
        $tpl = new Template('pages/main.inc');
        if ($blockID==='imageitems') {
            $tpl = new Template('pages/main_catalog.inc');
        }
        $tpl->vars['current']['title'] = $parentTitle;
        $tpl->vars['allowedit'] = (in_array($pagesBlock->getProp('name'), $this->AllowAdd)?'true':'');
        $tpl->vars['allowmove'] = (in_array($pagesBlock->getProp('name'), $this->AllowMove)?'true':'');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['block_id'] = $blockID;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $tpl->vars['ordering'] = array_key_exists($pagesBlock->getProp('name'), $this->orderArr)?$this->orderArr[$pagesBlock->getProp('name')]:'{ field: "pos", dir: "ASC" }';
        $tpl->vars['showdate'] = in_array($pagesBlock->getProp('name'), $this->dateArr)?'true':'';
        $tpl->vars['parentid']=  (_REQINT('parent_id')>0?'&amp;parent_id='._REQINT('parent_id'):'');
        $tpl->vars['parent_id']=  (_REQINT('parent_id')>0?_REQINT('parent_id'):'');
        $this->contents = $tpl->Render();
    }

    
    public function Form() {
        
        
        $method = _REQUEST('act');
        $block_ID = _REQUEST('block_id');
        $parent_ID = _REQINT('parent_id');
              
        $tpl = new Template('pages/form.inc');
        $OrTplName = 'dummy';
        if ($method==='sedit') {
            $item = new PagesItem(_REQINT('id'));
            $item->Load();
            $item->parseSpecial();
            
            $block_ID = $item->getProp('block_id');
            $alias = $item->getProp('alias');
            $parent_ID = intval($item->getProp('parent_id'));
            $OrTplName = 'pages/form_'.str_replace('-', '_', strtolower($alias)).'.inc';
            
            $tpl->vars['item']= $item->toArray();
            if ($item->url!==null) {
                $tpl->vars['url'] = $item->url->toArray();
            }
            $file = ItemFile::loadFrom($item->ID,'pages');
            if ($file!==null) {
                $tpl->vars['file'] = $file->toArray();
            }
            if ($item->getProp('block_id')==='imageitems') {
                $tpl->vars['itemcats'] = SerilizeToJson($item->getCats());
            }
            
            $method = 'edit';
        } else {
            $method = 'add';
            $tpl->vars['itemcats'] = '[]';
        }
        
        if (Template::TplExists($OrTplName)) {
            $tpl->setTemplate($OrTplName);
        } elseif (array_key_exists ($block_ID, $this->editForms)) {
            $tpl->setTemplate('pages/'.$this->editForms[$block_ID].'.inc');
        }
        
        
        $block = new PagesBlock($block_ID);
        $block->Load();
        
        if ($block_ID==='imageitems') {
            $catsTpl = new Category(null);
            $catsCol = $catsTpl->Collection(true);
            $catsCol->ordering = 'pos';
            $tpl->vars['cats'] = SerilizeToJson($catsCol->Load());
            
            $tags = PagesItem::getTags();
            $tpl->vars['tags'] = $tags['tags'];
            
        }
        
        $tpl->vars['act'] = $method;
        $tpl->vars['current']['pageparams'] = $this->pageUrl;
        $tpl->vars['current']['appname']= _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $tpl->vars['block'] = $block->toArray();
        if (array_key_exists($block_ID, $this->imageSettings)) {
            $tpl->vars['imageset'] = $this->imageSettings[$block_ID]['image'];
            $tpl->vars['thumbset'] = $this->imageSettings[$block_ID]['thumb'];
        }
        $tpl->vars['parent_id'] = $parent_ID>0?$parent_ID:'';
        $tpl->vars['mapkey'] = isLocalHost()?'':Configuration::Load('mapkey');
        $this->contents = $tpl->Render();
    }
    
    
    
    public function Edit() {
        $item = new PagesItem(_POSTINT('id'));
        $item->Load();
        $item->UpdateFromOv(_POST('item'), $_POST['url']);
        $postResult = $this->PageEdited($item);
        $params = array();
        $params['result']='ok';
        $params['data'] = $postResult;
        $this->contents = SerilizeToJson($params);
    }
    
    public function Add() {
        $block = new PagesBlock(filterInput($_POST['item']['block_id']));
        $block->Load();
        $item = new PagesItem(null);
        if (!in_array($block->ID, $this->AllowAdd))
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        
        $filters = array('block_id'=>$block->ID);
        if (isset($_POST['item']['parent_id']) && intval($_POST['item']['parent_id'])>0) {
            $filters = array('parent_id'=>intval($_POST['item']['parent_id']));
        }
        $item->InsertFromOv(_POST('item'),_POST('url'),$filters);
        $this->PageAdded($item);
        $params = array();
        $params['result']='ok';
        $params['reload'] = $this->QueryParams;
        $params['reload'][] =array('key'=>'act','value'=>'sedit');
        $params['reload'][] =array('key'=>'voider','value'=>'form');
        $params['reload'][] =array('key'=>'id','value'=>$item->ID);
        $this->contents = SerilizeToJson($params);
    }
    
    public function Move() {
        $item = new PagesItem(_POSTINT('id'));
        $item->Load();
        $filters = array('block_id'=>$item->getProp('block_id'));
        $itemParentID = $item->getProp('parent_id');
        if ($itemParentID>0) {
            $filters['parent_id'] = $itemParentID;
        }
        if (!in_array($item->getProp('block_id'), $this->AllowMove))
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        $item->Move(_REQUEST('direction'),$filters);
        $this->contents = true;
    }
    
    public function Visible() {
        $page = new PagesItem(_POSTINT('id'));
        $page->Load();
		/*
        if (!in_array($page->getProp('block_id'), $this->AllowAdd))
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
		*/
        $page->ChangeVisible(_POSTINT('value')===1?0:1);
        $this->contents = true;
    }
    
    public function Delete() {
        $ids = array(2);
        if (in_array(_POSTINT('id'),$ids)) {
            throw new EPageError(EPageError::CUSTOM_ERROR,'Unable to delete fixed pages.');
        }
        $page = new PagesItem(_POSTINT('id'));
        $page->Load();
        if (!in_array($page->getProp('block_id'), $this->AllowAdd))
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        if ($page->hasChildren()) {
            throw new EPageError(EPageError::CUSTOM_ERROR,'This page has sub pages, please delete subpages first');
        }
        
        $file = ItemFile::loadFrom($page->ID, 'pages');
        if ($file!==null) {
            $file->Remove();
        }
        ItemFile::deleteItemCollection($page->ID,Configuration::Load('items_pagefolder'),'pages');
        
        $page->Remove();
        $this->contents = true;
    }
    
    public function Detach() {
        $fileType = _POST('type');
        $item = new PagesItem(_POSTINT('itemid'));
        $item->Load();

        $file = new ItemFile(_POSTINT('id'));
        $file->Load();

        if (_VARINT($file->getProp('parent_id'))!==_VARINT($item->ID) && $file->getProp('type')!=='pages')
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);

        
        if ($fileType==='image') {
            $file->Removefile(false);
        } else if ($fileType==='thumb') {
            $file->Removefile(true);
        } else {
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        }
        $this->contents = SerilizeToJson(array('success'=>true,'filetype'=>$fileType));
    }


    public function Files() {
        $group = $_REQUEST['group'];
        $ItemsTemplate = new ItemFile(null);
        $ItemsTemplate->setProp('parent_id', _REQINT('parent_id'));
        $ItemsTemplate->setProp('type', 'pages-'.$group);
        $this->contents = $ItemsTemplate->getJsonCollection(0, 0, 0, '');
    }

    
    
    public function Attach() {
        $files = $_FILES['files'];
        if (!isset($files['name']))
            throw new EPageError(EPageError::CUSTOM_ERROR, 'file not selected');

        $itemID = _REQINT('id');
        $item = new PagesItem($itemID);
        $item->Load();
        
        $group = $_REQUEST['group'];
        $block = $item->getProp('block_id');
        if (!array_key_exists($block.'-'.$group, $this->imageSettings)) {
            throw new EPageError(EPageError::CUSTOM_ERROR, 'Wrong request');
        }
                
        $imageConf = $this->imageSettings[$block.'-'.$group];
        
        $ok = $item->AttachFiles($files,$imageConf['image'],$imageConf['thumb'],$group,$imageConf['maxsize']);
        $this->contents = $ok;
    }


    public function Detachgal() {
        $file = new ItemFile(_POSTINT('id'));
        $file->Load();
        $file->Remove();
        $this->contents = true;
    }
    
    
    public function Setlink() {
        ItemFile::setDescrs(_POSTINT('id'), $_POST['values']);
        $this->contents = true;
    }

    public function Names() {
        $items =  PagesItem::getBlock(_REQUEST('block_id'));
        array_unshift($items, array(
            'id'=>0,
            'name'=>'--Select--'
        ));
        $this->contents = SerilizeToJson($items);
    }

    /**
     * 
     * @param PagesItem $page
     * @return Array
     */
    private function PageAdded($page) {
        $file = null;
        if (array_key_exists($page->getProp('block_id'), $this->imageSettings)) {
            $imageSettings = $this->imageSettings[$page->getProp('block_id')];
            $Folders = Configuration::BulkLoad(array('items_tempfolder','items_pagefolder'));
            $pagesFolder = $Folders['items_pagefolder'].$page->ID.'/';
            
            $fileMode = ItemFile::UPLOAD_MODE_FILE;
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $file = ItemFile::CreateEmpty($page->ID, 'pages');
                if ($imageSettings['createthumb']===true) {
                    $fileMode = ItemFile::UPLOAD_MODE_BOTH;
                    $file->handleImageUpload($_FILES['image'], $pagesFolder, $Folders['items_tempfolder'], $fileMode , $imageSettings['image'],$imageSettings['thumb'],$imageSettings['ext']);
                } else {
                    $file->handleImageUpload($_FILES['image'], $pagesFolder, $Folders['items_tempfolder'], $fileMode , $imageSettings['image'],null,$imageSettings['ext']);
                }
            }
            if ($fileMode!==ItemFile::UPLOAD_MODE_BOTH && isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
                if ($file===null) {
                    $file = ItemFile::CreateEmpty($page->ID, 'pages');
                }
                $file->handleImageUpload($_FILES['thumb'], $pagesFolder, $Folders['items_tempfolder'], ItemFile::UPLOAD_MODE_THUMB , null, $imageSettings['thumb'],$imageSettings['ext']);
            }
            
            if (($imageSettings['thumb']===true || $imageSettings['image']===true) && isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                if ($file===null) {
                    $file = ItemFile::CreateEmpty($page->ID, 'pages');
                }
                $field = $imageSettings['thumb']===true?'thumbname':'filename';
                $file->handleFileUpload($_FILES['file'], $pagesFolder, $field);
            }
            
            
        }
        $ret = array();
        if ($file!==null) {
            $ret = $file->toArray ();
        }
        return $ret;
    }
    
    /**
     * 
     * @param PagesItem $page
     * @return Array
     */
    private function PageEdited($page) {
        $file = null;
        if (array_key_exists($page->getProp('block_id'), $this->imageSettings)) {
            $imageSettings = $this->imageSettings[$page->getProp('block_id')];
            $Folders = Configuration::BulkLoad(array('items_tempfolder','items_pagefolder'));
            $pagesFolder = $Folders['items_pagefolder'].$page->ID.'/';
            
            $existsID = ItemFile::FinFileID($page->ID, 'pages');
            if (_VARINT($existsID)>0) {
                $file = new ItemFile($existsID);
                $file->Load();
            }
            $fileMode = ItemFile::UPLOAD_MODE_FILE;
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                if ($file===null) {
                    $file = ItemFile::CreateEmpty($page->ID, 'pages');
                }
                if ($imageSettings['createthumb']===true) {
                    $fileMode = ItemFile::UPLOAD_MODE_BOTH;
                    $file->handleImageUpload($_FILES['image'], $pagesFolder, $Folders['items_tempfolder'], $fileMode , $imageSettings['image'],$imageSettings['thumb'],$imageSettings['ext']);
                } else {
                    $file->handleImageUpload($_FILES['image'], $pagesFolder, $Folders['items_tempfolder'], $fileMode , $imageSettings['image'],null,$imageSettings['ext']);
                }
            }
            if ($fileMode!==ItemFile::UPLOAD_MODE_BOTH && isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
                if ($file===null) {
                    $file = ItemFile::CreateEmpty($page->ID, 'pages');
                }
                $file->handleImageUpload($_FILES['thumb'], $pagesFolder, $Folders['items_tempfolder'], ItemFile::UPLOAD_MODE_THUMB , null, $imageSettings['thumb'],$imageSettings['ext']);
            }
            
            if (($imageSettings['thumb']===true || $imageSettings['image']===true) && isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                if ($file===null) {
                    $file = ItemFile::CreateEmpty($page->ID, 'pages');
                }
                $field = $imageSettings['thumb']===true?'thumbname':'filename';
                $file->handleFileUpload($_FILES['file'], $pagesFolder, $field);
            }
        }
        $ret = array();
        if ($file!==null) {
            $ret = $file->toArray ();
        }
        return $ret;
        // write image remove method
    }
    
    
    
    private function generateFakeItems($blockID) {
            
            $titles = array(
                'The world',
                'City',
                'Person',
                'Armenia',
                'Industry');
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

            /*
            $props = array(
                'propsa'=>array(1,2,4,8,16,32,64,128,256),
                'payments'=>array(1,2,4,8,16),
                'persons'=>array(1,2,4,8,16)
            );
             * 
             */
            
            $categories = array();
            $DB->Query("SELECT id FROM #__categories");
            while ($row = $DB->ReadRow()) {
                $categories[] = $row['id'];
            }
            $_CT = count($categories)-1;
            
            
            $confItems = Configuration::BulkLoad(array('items_pagefolder', 'items_tempfolder'));
            $folder = $confItems['items_pagefolder'];
            $imageMax = $this->imageSettings[$blockID]['image'];
            $imageThumb = $this->imageSettings[$blockID]['thumb'];
            $imageSettings = $imageThumb;
            
            
            $totalCount = 60;
            for ($i = 1; $i < $totalCount; $i++) {

                $tidx = rand(0,$_LT);
        
                $item = array();
                
                $item['name'] = ucfirst(getRandomTitle());
                $catsCount = rand(1,$_CT);
                $aa = $categories;
                shuffle($aa);
                $item['cats'] = array();
                for($c=0; $c<$catsCount;$c++) {
                    $item['cats'][] = $aa[$c];
                }
                
                $item['ainfo'] = ucfirst($titles[$tidx]);
                $item['binfo'] = 'Is simple dummy text for printing';
                
                $item['block_id'] = $blockID;
                
                $item['date_object'] = getRandomDate('01.01.2016', '23.02.2017'); 
                $item['descr'] = getRandomDescr(20);
                $item['content'] = getLorem();
                $item['tags'] =  implode(',',getRandomTags(5, 12));
                
                /*
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
                */
                
                
                $urlProps = array();
                $urlProps['alias'] = 'article-2-'.$i;
                $urlProps['url'] = 'articles/article-2-'.$i;
                $urlProps['meta_title'] = $item['name'];   
                $urlProps['componenttype'] = 'pages';
                $prod = new PagesItem(null);
                // $filters = array('block_id'=>$blockID);
                $filters = array('parent_id'=>5);
                $prod->InsertFromOv($item,$urlProps,$filters);
               
                // files attach
                $UploadDir = $folder . $prod->ID . '/';

                if (!is_dir( ROOTDIR . $UploadDir)) {
                    mkdir( ROOTDIR . $UploadDir, 4664);
                }
                $id = $prod->ID;
                
                shuffle($files);
                $imagePath = $files[0]['filepath'];
                $fileParts = pathinfo($imagePath);
                $fileParts['filename'] = str_replace(array('(',')',' '),array('','','_'), $fileParts['filename']);
                $DB->Query("insert into #__files
                    (parent_id, type, filename)
                    values ('" . $id . "', 'pages', 'dummy')");
                $FileID = intval($DB->LastID());
                
                // $targetName = $UploadDir.$fileParts['filename'].'_'.$j.'.jpg'; // .$fileParts['extension']
                $targetName = $UploadDir.$fileParts['filename'].'_'.$i.'_thumb.jpg'; // .$fileParts['extension']
                $imgManage = new ImageManager($imagePath);
                $mem = $imgManage->resizeImage(intval($imageSettings['w']), intval($imageSettings['h']), $imageSettings['mode'], false);
                if (!$mem)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');
                $ok = $imgManage->saveImage(ROOTDIR .$targetName, 100);
                if (!$ok)
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'unable to resize image');
                list($imw, $imh) = getimagesize(ROOTDIR.$targetName);
                $fleName = $DB->EscapeValue($targetName);
                $DB->Query("UPDATE #__files SET filename=NULL,
                                                thumbname=" . $fleName . ",
                                                img_width = 0, 
                                                img_height = 0, 
                                                tmb_width = " . intval($imw) . ", 
                                                tmb_height = " . intval($imh) . ", 
                                                mime_type = NULL, 
                                                mime_thumb = 'image/jpg'   
                                    WHERE id=" . $FileID);

                    
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