<?php
class configWebPage extends MasterWebPage {
    
    protected $Styles = array();
    
    

    public function __construct() {
        parent::__construct();
    }
    
    public function Index() {
        if (_REQUEST('act') == 'save') {
            $item = new ConfigurationItem(_REQINT('id'));
            $item->setProp('value', stripcslashes(htmlspecialchars(_REQUEST('value'),ENT_QUOTES)));
            $ok = $item->Update();
            $item->Load();
            $item->ApplyChanges();
            if (!$ok) {
                throw new EPageError(EPageError::CUSTOM_ERROR,'error saving data');
            } else {
                $this->contents = '{ "success" : "true" }';
            }
        } else {
            $ConfigGroup = new ConfigurationGroup(_REQINT('parent_id'));
            $ConfigGroup->Load();
            $ConfigGroup->LoadChilds();
            $tpl = new Template('config/main.inc');
            $tpl->vars['items'] = $ConfigGroup->items;
            $tpl->vars['alias'] = $ConfigGroup->getProp('name');
            $tpl->vars['parent_id'] = $ConfigGroup->ID;
            $this->contents = $tpl->Render();
        }
        
        /*
         *  $itemTemplate = new configItem();
         *  $itemTemplate->setProp('pageAlias','test');
         *  $collection = $itemTemplate->getCollection();
         *  $collection->load();
         *? $tpl->vars['items'] = $collection
         * 
         *  $collection['Key']->setProp('value','test');
         *  $collection['key']->delete();
         * 
         *  $item = new configItem($id);
         *  $item->setProp('value','test');
         *  $item->save();
         * 
         *? $tpl->vars['config'] = $item 
         */
        
    }
    
    
    
    
    
    
    
    
    
   

}

?>