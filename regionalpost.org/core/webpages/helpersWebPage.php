<?php
 class helpersWebPage extends MasterWebPage {

    public function preVoid() {
        return false;
    }
     
    public function TestCookie() {
        HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
        $tc = _POSTINT('tc');
        if (empty($tc)) {
            cookieManager::writeTestCookie();
            
            $tz = _POST('tz');
            try {
                $offset = convertToOffset($tz);
            } catch (Exception $e) {
                $offset = '+00:00';
            }
            
            $_SESSION['client_tz'] = $offset;
            
            $this->contents = '{"result" : "ok" }';
        } else {
            $ok = cookieManager::checkTestCookie();
            $this->contents = '{"result" : "ok" '.($ok===true?', "cookie":"ok"':'').' }';
        }
    }

    public function JSApp() {
        $tpl = new Template('app.js');
        // $_SESSION['tmp'] = 0;
        $tpl->vars['worlist'] = SerilizeToJson(Dictionary::BulkLoad(array('ERR_REQUIREDFIELD')));
        
        $tpl->vars['standalone'] = _REQINT('al')>0?'true':'';
        $tpl->vars['notstandalone'] = _REQINT('al')>0?'':'true';
        
        $langList =  HttpContext::current()->culture()->LanguageList();
        $currLang = $langList[HttpContext::current()->culture()->Language()];
        unset($langList[HttpContext::current()->culture()->Language()]);
        $modLangList = array();
        foreach ($langList as $oneLang) {
            $modLangList[] = array(
                'text'=>$oneLang['short'], 'url'=> $oneLang['id'].'/', 'imageUrl'=>'imgs/langs/'.$oneLang['id'].'.jpg'
            );
        }
        $tpl->vars['langlist'] = SerilizeToJson($modLangList);
        $tpl->vars['currentlang'] = $currLang;
        
        $currencyList = HttpContext::current()->culture()->CurrencyList();
        $currCurrency = $currencyList[HttpContext::current()->culture()->Currency()];
        unset($currencyList[HttpContext::current()->culture()->Currency()]);
        $modCurrList = array();
        foreach ($currencyList as $key=>$oneCurr) {
            $modCurrList[] = array(
                'text'=>$oneCurr['short'], 'url'=> '?_set_currency='.$key
            );
        }
        $tpl->vars['currlist'] = SerilizeToJson($modCurrList);
        $tpl->vars['currentcurr'] = $currCurrency;
        $tpl->vars['culture']= HttpContext::current()->culture()->Culture();
        
        $tpl->vars['urlalis'] = HttpContext::current()->request()->alias;
        $tpl->vars['urlid'] = HttpContext::current()->request()->urlID;
        $tpl->vars['identified'] = HttpContext::current()->Identified()?'true':'false';
        
        
        $tpl->vars['th'] = md5($_SESSION['R_THUMBPRINT']);
        $tpl->vars['site_refresh'] = Configuration::Load('site_refresh');
        $this->contents = $tpl->Render();
    }
    
    
    public function setListViewType() {
        $tpe = _POST('tpe');
        $type = 'thumb';
        if ($tpe==='list') {
            $type = 'list';
        } 
        cookieManager::writeCookie('listtype', $type);
    }
    
   
    
    /*
    public function setCurrency() {
        HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
        $currency = _POST('currency');
        HttpContext::current()->culture()->setCurrency($currency);
        $ret = array();
        $ret['reload'] = array();
        $ret['method'] = 'POST';
                
    }
    */
    }
?>
