<?php


    class adminmembersWebPage extends MasterWebPage {
        
    protected $Styles = array('css/adminmembers.css');
    protected $pageParams =array("show");
    
    protected $publicMethods = array('Index','Form','Edit','Add','Delete','Passform','Togglemember');
    
    
    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $field = $_REQUEST['sort'][0]['field'];
                if ($field==='name')
                    $field='tu.name';
                if ($field==='role_name')
                    $field='tr.name';
                if ($field==='id')
                    $field='tu.id';
                if ($field==='daysleft')
                    $field='tu.validdate';
                $orderby = $field . ' ' . $_REQUEST['sort'][0]['dir'];
            }
            $pageSize = _REQINT('pageSize');
            $skip = _REQINT('skip');
            $page = _REQINT('page');

            $ItemsTemplate = new AdminMember(null);
            $this->contents = SerilizeToJson($ItemsTemplate->LoadCollection($pageSize, $skip, $page, $orderby));
            return;
        }
        
        $tpl = new Template('adminmembers/main.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    
    public function Form() {
        $method = _REQUEST('act');
        $tpl = new Template('adminmembers/form.inc');
        $id = _REQINT('id');
        
        $rol = new Role(null);
        $rols = $rol->Collection(true);
        $rols->Load(true, 0, 0, false);
        $roleArr = $rols->toArray();

        if ($method==='sedit') {
            $item = new AdminMember($id);
            $item->Load();
            $tpl->vars['item']= $item->toArray();
            $roleID = _VARINT($item->getProp('role_id'));
            $roleArr[$roleID]['selected'] = 'selected="selected"';
            $method='edit';
        } else {
            $method='add';
        }
        $tpl->vars['roles'] = $roleArr;
        $tpl->vars['act'] = $method;
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    public function Add() {
        if (_VARINT($_POST['item']['role_id'])<=0) {
            throw new EInvalidInputError(array('member-role_id'));
        }
        $role = new Role(_VARINT($_POST['item']['role_id']));
        $role->Load();
        $item = new AdminMember(null);
        $item->Add(_POST('item'));
        $params = array();
        $params['result']='ok';
        $params['reload'] = $this->QueryParams;
        $params['reload'][] =array('key'=>'show','value'=>'adminmembers');
        $this->contents = SerilizeToJson($params);
    }
    
    public function Edit() {
        if (_VARINT($_POST['item']['role_id'])<=0) {
            throw new EInvalidInputError(array('member-role_id'));
        }
        $role = new Role(_VARINT($_POST['item']['role_id']));
        $role->Load();
        $item = new AdminMember(_POSTINT('id'));
        $item->EditProperties(_POST('item'));
        $this->contents = true;
    }
    
    public function Togglemember() {
        $id = _POSTINT('id');
        $isEnabled = _POSTINT('enabled')==1?true:false;
        if (HttpContext::current()->Identity()->ID == $id)
            throw new EPageError(EPageError::CUSTOM_ERROR, 'You cannot disable yourself');
        $item = new AdminMember($id);
        $item->Load();
        $item->ToggleEnabled($isEnabled);
        $this->contents = true;
    }
    
    public function Delete() {
        $id = _POSTINT('id');
        if (HttpContext::current()->Identity()->ID == $id)
            throw new EPageError(EPageError::CUSTOM_ERROR, 'You cannot delete yourself');
        $item = new AdminMember(_POSTINT('id'));
        $ok = $item->Remove();
        $this->contents = true;
    }
    
   
    public function Passform() {
        if (HttpContext::current()->request()->IsJson()) {
            $item = new AdminMember(HttpContext::current()->Identity()->ID);
            $item->Load();
            $item->ChangePass(_POST('pass'), _POST('passa'), _POST('passb'));
            $this->contents = true;
            return;
        }
        $tpl = new Template('adminmembers/passchange.inc');
        $this->contents = $tpl->Render();
    }
    
}

?>
