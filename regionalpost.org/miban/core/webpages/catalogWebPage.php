<?php

    class catalogWebPage extends MasterWebPage {

    protected $pageParams =array("show");
    protected $Styles = array('css/catalog/catalog.css');
 
    protected $publicMethods = array('Index','Form','Edit','Add','Move','Visible','Delete','Detach');
    
    private $imageSettings = array(
        'createthumb'=>false,
        'thumb'=>array('mode'=>'smarty','w'=>98,'h'=>98,'maxsize'=>false),
        'image'=>array('mode'=>'smarty','w'=>1080,'h'=>370,'maxsize'=>false),
        'ext'=>'jpg',
        'thumbext'=>'*'
    );
    
    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            if (!isset($_REQUEST['id'])) {
                $this->contents = SerilizeToJson(array(array(
                    'id'=>0,
                    'Children'=>true,
                    'name'=>'All'
                )));
                return;
            }
            
            $pid = _REQINT('id');
            $categoryTemplate = new Category(null);
            $categoryTemplate->setProp('parent_id',$pid);
            $nid = _REQINT('nid');
            $json = $categoryTemplate->getJsonCollectionOv($nid);
            $this->contents = $json;
            return;
        }

        $tpl = new Template('catalog/main.inc');
        $selId = _REQINT('id');
        if ($selId>0) {
            try {
                $selCat = new Category($selId);
                $selCat->Load();
                $tpl->vars['selcat'] = $selCat->toArray();
            } catch (Exception $ex) {
                // nothing
            }
        }
        $tpl->vars['current']['title'] = '';
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    
    public function Form() {
        $method = _REQUEST('act');
        $tpl = new Template('catalog/category_form.inc');
        $id = _REQINT('id');
        $pid = _REQINT('parent_id');
        
        if ($method==='sedit') {
            $item = new Category($id);
            $item->Load();
            $tpl->vars['item']= $item->toArray();
            $pid=_VARINT($item->getProp('parent_id'));
            $tpl->vars['url'] = $item->url->toArray();
            $method = 'edit';
            $file = ItemFile::loadFrom($item->ID,'category');
            if ($file!==null) {
                $tpl->vars['file'] = $file->toArray();
            }
        }else {
            $method = 'add';
        }
        if ($pid>0) {
            $parent_item = new Category($pid);
            $parent_item->Load();
            $tpl->vars['parent']= $parent_item->toArray();
        } else {
            $tpl->vars['parent']['name'] = 'Root category';
            $tpl->vars['parent']['id'] = 0;
        }
        
        $tpl->vars['act'] = $method;
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    public function Edit() {
        $item = new Category(_POSTINT('id'));
        $item->UpdateFromOv(_POST('item'), _POST('url'));
        $postResult = $this->itemChanged($item);
        $params = array();
        $params['result']='ok';
        $params['data'] = $postResult;
        $this->contents = SerilizeToJson($params);
    }
    
    public function Add() {
        $item = new Category(null);
        $item->InsertFromOv(_POST('item'),_POST('url'));
        $this->itemChanged($item);
        $params = array();
        $params['result']='ok';
        $params['reload'] = $this->QueryParams;
        $params['reload'][] =array('key'=>'act','value'=>'sedit');
        $params['reload'][] =array('key'=>'voider','value'=>'form');
        $params['reload'][] =array('key'=>'id','value'=>$item->ID);
        $this->contents = SerilizeToJson($params);
    }
    
    
    
    
    
    public function Move() {
        $item = new Category(_POSTINT('id'));
        $item->Load();
        $filters = array('parent_id'=>_VARINT($item->getProp('parent_id')));
        $item->Move(_REQUEST('direction'),$filters);
        $this->contents = true;
    }
    
    public function Visible() {
        $item = new Category(_POSTINT('id'));
        $item->ChangeVisible(_POSTINT('value'));
        $this->contents = true;
    }
    
    public function Delete() {
        $item = new Category(_POSTINT('id'));
        $item->Remove();
        $this->contents = true;
    }
    
    public function Detach() {
        $fileType = _POST('type');
        $item = new Category(_POSTINT('itemid'));
        $item->Load();

        $file = new ItemFile(_POSTINT('id'));
        $file->Load();

        if (_VARINT($file->getProp('parent_id'))!==_VARINT($item->ID) && $file->getProp('type')!=='category')
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

    
    private function itemChanged($item) {
        $file = null;
        $Folders = Configuration::BulkLoad(array('items_tempfolder','items_catfolder'));
        $folder = $Folders['items_catfolder'].$item->ID.'/';
        $existsID = ItemFile::FinFileID($item->ID, 'category');
        if (_VARINT($existsID)>0) {
            $file = new ItemFile($existsID);
            $file->Load();
        }
        $fileMode = ItemFile::UPLOAD_MODE_FILE;
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            if ($file===null) {
                $file = ItemFile::CreateEmpty($item->ID, 'category');
            }
            if ($this->imageSettings['createthumb']===true) {
                $fileMode = ItemFile::UPLOAD_MODE_BOTH;
                $file->handleImageUpload($_FILES['image'], $folder, $Folders['items_tempfolder'], $fileMode , $this->imageSettings['image'],$this->imageSettings['thumb'],$this->imageSettings['ext']);
            } else {
                $file->handleImageUpload($_FILES['image'], $folder, $Folders['items_tempfolder'], $fileMode , $this->imageSettings['image'],null,$this->imageSettings['ext']);
            }
        }
        if ($fileMode!==ItemFile::UPLOAD_MODE_BOTH && isset($_FILES['thumb']['name']) && !empty($_FILES['thumb']['name'])) {
            if ($file===null) {
                $file = ItemFile::CreateEmpty($item->ID, 'category');
            }
            $file->handleImageUpload($_FILES['thumb'], $folder, $Folders['items_tempfolder'], ItemFile::UPLOAD_MODE_THUMB , null, $this->imageSettings['thumb'],$this->imageSettings['thumbext']);
        }
        if ($this->imageSettings['thumb']===true && isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            if ($file===null) {
                $file = ItemFile::CreateEmpty($item->ID, 'category');
            }
            $file->handleFileUpload($_FILES['file'], $folder, 'thumbname');
        }
        
        $ret = array();
        if ($file!==null) {
            $ret = $file->toArray ();
        }
        return $ret;
    }
    
    /*
    private function Categorytree() {
        // category tree in window
        $tpl = new Template('catalog/treeview.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $tpl->vars['nid'] = _REQINT('id');
        $this->contents = $tpl->Render();
    }
    
    private function itemtree() {
        // cats and products in window
        $tpl = new Template('catalog/itemtree.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }*/
    
}
?>