<?php
  // changed
    class slidesWebPage extends MasterWebPage {

    protected $publicMethods = array('Index','Form','Edit','Add','Move','Visible','Delete','Detach');
    
    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $orderby = $_REQUEST['sort'][0]['field'].' '.$_REQUEST['sort'][0]['dir'];
            }
            $pageItemsTemplate = new SlideItem(null);
            $this->contents = $pageItemsTemplate->getJsonCollection(_REQINT('pageSize'), _REQINT('skip'), _REQINT('page'), $orderby);
            return;
        }
        $tpl = new Template('slides/main.inc');
        $tpl->vars['current']['title'] = '';
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    public function Form() {
        $method = _REQUEST('act');
        $tpl = new Template('slides/form.inc');
        if ($method==='sedit') {
            $item = new SlideItem(_REQINT('id'));
            $item->Load();
            $tpl->vars['item']= $item->toArray();
            $file = ItemFile::loadFrom($item->ID,'slides');
            if ($file!==null) {
                $tpl->vars['file'] = $file->toArray();
            }
            $method = 'edit';
        } else {
            $method = 'add';
        }
        $img = Configuration::Load('image_slide');
        $tpl->vars['imageset'] = $img;
        $tpl->vars['act'] = $method;
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    public function Edit() {
        $item = new SlideItem(_POSTINT('id'));
        $item->UpdateFromOv(_POST('item'));
        $postResult = $this->ItemEdited($item);
        $params = array();
        $params['result']='ok';
        $params['data'] = $postResult;
        $this->contents = SerilizeToJson($params);
    }
    
    public function Add() {
        $item = new SlideItem(null);
        $item->InsertFromOv(_POST('item'));
        $this->ItemAdded($item);
        $params = array();
        $params['result']='ok';
        $params['reload'] = $this->QueryParams;
        $params['reload'][] =array('key'=>'voider','value'=>'form');
        $params['reload'][] =array('key'=>'act','value'=>'sedit');
        $params['reload'][] =array('key'=>'id','value'=>$item->ID);
        $this->contents = SerilizeToJson($params);
    }
    
    public function Move() {
        $item = new SlideItem(_POSTINT('id'));
        $item->Load();
        $item->Move(_REQUEST('direction'));
        $this->contents = true;
    }
    
    public function Visible() {
        $page = new SlideItem(_POSTINT('id'));
        $page->ChangeVisible(_POSTINT('value'));
        $this->contents = true;
    }
    
    public function Delete() {
        $page = new SlideItem(_POSTINT('id'));
        $page->Load();
        
        $file = ItemFile::loadFrom($page->ID, 'slides');
        if ($file!==null) {
            $file->Remove();
        }
        
        $page->Remove();
        $this->contents = true;
    }
    
    public function Detach() {
        $fileType = _POST('type');
        $item = new SlideItem(_POSTINT('itemid'));
        $item->Load();

        $file = new ItemFile(_POSTINT('id'));
        $file->Load();

        if (_VARINT($file->getProp('parent_id'))!==_VARINT($item->ID))
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);

        if ($fileType==='image') {
            $file->Removefile(false);
        } else if ($fileType==='thumb') {
            $file->Removefile(true);
        } else {
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        }
        $this->contents =true;
    }

    
    
    /**
     * 
     * @param SlideItem $page
     * @return Array
     */
    private function ItemAdded($page) {
        $file = null;
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $conf = Configuration::BulkLoad(array('items_tempfolder','items_slidefolder','image_slide'));
            $file = ItemFile::CreateEmpty($page->ID, 'slides');
            $file->handleImageUpload($_FILES['image'], $conf['items_slidefolder'], $conf['items_tempfolder'],  ItemFile::UPLOAD_MODE_FILE  , $conf['image_slide']);
        }

        $ret = array();
        if ($file!==null) {
            $ret = $file->toArray ();
        }
        return $ret;
    }
    
    /**
     * 
     * @param SlideItem $page
     * @return Array
     */
    private function ItemEdited($page) {
        $conf = Configuration::BulkLoad(array('items_tempfolder','items_slidefolder','image_slide'));
        
        $file = null;
        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
            $file = ItemFile::loadFrom($page->ID, 'slides');
            if ($file===null) {
                $file = ItemFile::CreateEmpty($page->ID, 'slides');
            }
            $file->handleImageUpload($_FILES['image'], $conf['items_slidefolder'], $conf['items_tempfolder'],  ItemFile::UPLOAD_MODE_FILE  , $conf['image_slide']);
        }
       
        $ret = array();
        if ($file!==null) {
            $ret = $file->toArray ();
        }
        return $ret;
        // write image remove method
    }
    
    
    }

?>
