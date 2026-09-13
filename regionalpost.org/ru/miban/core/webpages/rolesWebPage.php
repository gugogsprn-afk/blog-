<?php
/**
 * 
 *
 * @author Sight©
 */
class rolesWebPage extends MasterWebPage {
    
    protected $Styles = array('css/adminmembers.css');
    protected $pageParams =array("show");
    
    protected $publicMethods = array('Index','Form','Edit','Add','Delete');
    
    public function Index() {
        if (HttpContext::current()->request()->IsJson()) {
            $orderby = '';
            if (isset($_REQUEST['sort'][0]['field'])) {
                $orderby = $_REQUEST['sort'][0]['field'] . ' ' . $_REQUEST['sort'][0]['dir'];
            }
            $pageSize = _REQINT('pageSize');
            $skip = _REQINT('skip');
            $page = _REQINT('page');

            $ItemsTemplate = new Role(null);
            $this->contents = SerilizeToJson($ItemsTemplate->LoadCollection($pageSize, $skip, $page, $orderby));
            return;
        }
        $tpl = new Template('roles/main.inc');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        
        $this->contents = $tpl->Render();
    }
    
    
    public function Form() {
        $tpl = new Template('roles/form.inc');
        $id = _REQINT('id');
        $currentPerms = array();
        $method = _REQUEST('act');
        if ($method==='sedit') {
            $item = new Role($id);
            $item->Load();
            $currentPerms = $item->toArray();
            $tpl->vars['item']= $currentPerms;
            $method='edit';
        } else {
            $method='add';
        }
        
        $cat = new Category(null);
        $cat->setProp('parent_id', '0');
        $catColl = $cat->Collection(true);
        $tpl->vars['cats'] = $catColl->Load(true);
        
        $tpl->vars['perms'] = Role::AllPermissions(false,$currentPerms);
        $tpl->vars['cperms'] = Role::getCompositeEditors($currentPerms, $tpl->vars['cats']);
        $tpl->vars['act'] = $method;
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $this->contents = $tpl->Render();
    }
    
    
    public function Edit() {
        $item = new Role(_POSTINT('id'));
        $data = $this->getInputData();
        $ok = $item->Edit($data);
        $this->contents = true;
    }
    
    public function Add() {
        $item = new Role(null);
        $data = $this->getInputData();
        $item->Add($data);
        $params = array();
        $params['result']='ok';
        $params['reload'] = array();
        $params['reload'][] =array('key'=>'voider','value'=>'form');
        $params['reload'][] =array('key'=>'act','value'=>'sedit');
        $params['reload'][] =array('key'=>'id','value'=>$item->ID);
        $this->contents = SerilizeToJson($params);
    }

    public function Delete() {
        $item = new Role(_POSTINT('id'));
        $item->Load();
        $item->Remove();
        $this->contents = true;
    }
    
    
    private function getInputData() {
        $data = array('name'=> _VAR($_POST['item']['name']),'descr'=> _VAR($_POST['item']['descr']));
        $allPerms = Role::AllPermissions(true);
        
        foreach ($allPerms as $permID => $row) {
            if ($row['permtype']==='p') {
                $inputPerm = $_POST['perm'][intval($permID)];
                $PermValue = 0;
                if (is_array($inputPerm)) {
                    $PermValue = intval($inputPerm[1]) | intval($inputPerm[2]) | intval($inputPerm[4]) | intval($inputPerm[8]);
                }
                $data['p_'.$row['alias']]=$PermValue;
            } else {
                
                $catsIDs = $_POST['cerm'][intval($permID)]['id'];
                $combined = array();
                if (array_isset($catsIDs)) {
                    foreach ($catsIDs as $catID) {
                        $inputPerm = $_POST['cerm'][intval($permID)][intval($catID)];
                        $PermValue = 0;
                        if (is_array($inputPerm)) {
                            $PermValue = intval($inputPerm[1]) | intval($inputPerm[2]) | intval($inputPerm[4]) | intval($inputPerm[8]);
                        }
                        if ($PermValue===0) continue;
                        $combined[$catID] = $PermValue;
                    }
                }
                $data['c_'.$row['alias']]= SerilizeToJson($combined);
            }
        }
        return $data;
    }

   }
?>