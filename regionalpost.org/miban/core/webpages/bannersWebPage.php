<?php

    class bannersWebPage extends MasterWebPage {

    protected $pageParams =array("show");
    protected $publicMethods = array('Index','Form','Edit','Add','Delete','Showmap');
    
    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $orderby = $_REQUEST['sort'][0]['field'].' '.$_REQUEST['sort'][0]['dir'];
            }
            $ItemsTemplate = new Banner(null);
            $this->contents = $ItemsTemplate->getJsonCollection(_REQINT('pageSize'), _REQINT('skip'), _REQINT('page'), $orderby);
            return;
        }
        $tpl = new Template('banners/main.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    public function Form() {
        $tpl = new Template('banners/form.inc');
        $method = _REQUEST('act');
        if ($method==='sedit') {
            $item = new Banner(_REQINT('id'));
            $item->Load();
            $tpl->vars['urls'] = $item->getPages();
            $tpl->vars['item']= $item->toArray();
            
            $method = 'edit';
        } else {
            $method = 'add';
        }
        $tpl->vars['act'] = $method;
        $tpl->vars['siteroot'] = HttpContext::current()->request()->siteBaseAddress;
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    
    public function Showmap() {
        if (HttpContext::current()->request()->IsJson()) {
            $pid = _REQINT('id');
            $json = UrlCache::getMapJson($pid);
            $this->contents = $json;
            return;
        }
        $tpl = new Template('banners/map.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    
   
    public function Edit() {
        $item = new Banner(_POSTINT('id'));
        $item->UpdateFromOv(_POST('item'), _POST('url'));
        $params = array();
        $params['result']='ok';
        $params['data'] = array();
        $this->contents = SerilizeToJson($params);
    }
    
    public function Add() {
        $item = new Banner(null);
        $item->InsertFromOv(_POST('item'),_POST('url'));
        $params = array();
        $params['result']='ok';
        $params['reload'] = $this->QueryParams;
        $params['reload'][] =array('key'=>'act','value'=>'sedit');
        $params['reload'][] =array('key'=>'voider','value'=>'form');
        $params['reload'][] =array('key'=>'id','value'=>$item->ID);
        $this->contents = SerilizeToJson($params);
    }
    
    public function Delete() {
        $page = new Banner(_POSTINT('id'));
        $ok = $page->Remove();
        $this->contents = true;
    }
    
    /** protected method 
    private function findParents() {
        $this->contents = Banner::BuildParents();
    }
    ***/
    
    
}

?>
