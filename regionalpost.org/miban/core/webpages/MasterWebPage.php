<?php

class MasterWebPage extends WebPageBase {
    
    protected $Styles = array();
    
    protected $pageParams =array("show");
    protected $pageUrl='';
    protected $QueryParams = array();
  
    protected $publicMethods = array("Index");
    
    protected $headersHandled = false;
    
    /**
     *
     * @var Template
     */
    protected $MainTpl = null;
    protected $contents = '';

    public function __construct() {
        parent::__construct();
    }
    
    public function Index() {
        $cont = new Template('main_page.inc');
        $this->contents = $cont->Render();
    }

    public function Initilize() {
        $this->MainTpl = new Template('main.inc');
        $this->MainTpl->vars['baseaddress'] = HttpContext::current()->request()->baseAddress;
        $this->MainTpl->vars['curlang'] = HttpContext::current()->culture()->Language();
        
        $params = array();
        foreach ($this->pageParams as $key) {
            $value = _REQUEST($key);
            $params[$key] = $value;
            $c = array();
            $c['key'] = $key;
            $c['value'] = $value;
            $this->QueryParams[] = $c;
        }
        $this->pageUrl = http_build_query($params);
    }
    
    /**
     * 
     * @return \Template
     */
    protected function getHeader() {
        $tpl = new Template('header.inc');
        
        $configGroupTemplate = new ConfigurationGroup(null);
        $configGroups = $configGroupTemplate->Collection(true);
        $configGroups->ordering='pos';
        $configGroups->Load();
        $tpl->vars['configgroups'] = $configGroups->toArray();
        
        
        $pageBlockTemplate = new PagesBlock(null);
        $pageBlocks = $pageBlockTemplate->Collection(true);
        $pageBlocks->ordering='pos';
        $pageBlocks->Load(true,0,0,true);
        $pageBlocksArr = $pageBlocks->toArray();
        
        $pageItemsTemplate = new PagesItem(null);
        $pageItemsTemplate->setProp('block_id', 'collection');
        $pageItems = $pageItemsTemplate->Collection(true);
        $tpl->vars['suba'] = PagesItem::getBlockforMenu('menusub',17);
        /*
        $pageItemsTemplate = new PagesItem(null);
        $pageItemsTemplate->setProp('block_id', 'collection');
        $pageItems = $pageItemsTemplate->Collection(true);
        $itemConstants = array(
            'main_block'=>'imageitems'
        );
        $pageItems->setConstants($itemConstants);
        $pageItems->Load(false,0,0,false);
        $pageItemsArr = $pageItems->toArray();
        
        $pageBlocksArr['collection']['subitems'] = $pageItemsArr;
        */
        
        $tpl->vars['pageblocks'] = $pageBlocksArr;
        
        $tpl->vars['currenturl'] = HttpContext::current()->request()->reletiveUrl;
        $tpl->vars['username'] = HttpContext::current()->Identity()->name;
        $tpl->vars['userid'] = HttpContext::current()->Identity()->ID;
        return $tpl;
    }
    
    
    /**
     * 
     * @return \Template
     */
    protected function getFooter() {
        $tpl = new Template('footer.inc');
        $tpl->vars['baseaddress'] = HttpContext::current()->request()->baseAddress.HttpContext::current()->request()->Language.'/';
        
        $year = date('Y');
        $yearInterval = (intval($year)!=2014?'2014 - '.$year:$year);
        $tpl->vars['years'] = $yearInterval;
        $tpl->vars['year'] = $year;
        
        return $tpl;
    }
    
    // TODO: implement permission logic
    public function checkPermissions() {
        return true;
    }
    
    public function isPublicMethod($methodName) {
        return $methodName=='' || in_array($methodName, $this->publicMethods);
    }
    
    
    public function Display() {
        $responseBlock = false;
header('Cache-Control: no-cache, must-revalidate');
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:');
        //header_remove("Last-Modified"); 
        if (HttpContext::current()->request()->isJS()) {
            header('Content-type: application/javascript; charset=utf-8');
            $this->headersHandled = true;
            $responseBlock = true;
        }
        if (HttpContext::current()->request()->IsAjax() || $responseBlock===true) {
            if ($this->headersHandled===false) {
                if (HttpContext::current()->request()->IsJson()) {
                    
                    header('Content-type: application/json');
                } else {
                    if (HttpContext::current()->request()->IsText()) {
                        header('Content-Type: text/plain; charset=utf-8');
                    } else {
                        header('Content-Type: text/html; charset=utf-8');
                    }
                }
            }
            if ($this->contents===true) {
                echo '{"result":"ok"}';
            } else {
                echo $this->contents;
            }
            return;
        } else {
            if ($this->headersHandled===false) {
                header('Content-Type: text/html; charset=utf-8');
            }
        } 
        
        $this->MainTpl->vars['header'] = $this->getHeader()->Render();
        $this->MainTpl->vars['footer'] = $this->getFooter()->Render();
        
        $this->MainTpl->vars['lang'] = HttpContext::current()->culture()->Language();
        $this->MainTpl->vars['culture'] = HttpContext::current()->culture()->Culture();
        
        $this->MainTpl->vars['firstload'] = (!isset($_SESSION['cookiesEnabled'])?'true':'');
        
        $this->MainTpl->vars['baseaddress'] = HttpContext::current()->request()->baseAddress;
        $this->MainTpl->vars['relativeurl'] = HttpContext::current()->request()->reletiveUrl;
        $this->MainTpl->vars['styles']=$this->Styles;
        $this->MainTpl->vars['contents']=$this->contents;
        $this->MainTpl->vars['rand'] = rand();
        echo $this->MainTpl->Render();
    }

        

    }

?>