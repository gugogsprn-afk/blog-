<?php
 class helpersWebPage extends MasterWebPage {

    protected $publicMethods = array('Translit','Urlbuilder','Jsapp','Urlmap','Tc');
    
    
    public function Translit() {
        $word = _POST('word');
        $st = translitWord($word);
        $this->contents = '{ "result" : "ok", "text" : "'.$st.'" }';
    }
    
    public function Urlbuilder() {
        HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
        $arr = UrlCache::BuildUrl(_POST('alias'),  _POST('itemtype'), _POST('parent_id'), _POST('mode'), $_POST);
        $this->contents = SerilizeToJson($arr);
    }
	
    public function JSApp() {
        HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JS);
        $tpl = new Template('jsapp.js');
        $tpl->vars['culture'] = HttpContext::current()->culture()->Culture();
        $tpl->vars['basehref'] = HttpContext::current()->request()->siteBaseAddress;
        $tpl->vars['timezone'] = HttpContext::current()->culture()->Timezone();
        $this->contents = $tpl->Render();
    }
    
    public function Urlmap() {
        HttpContext::current()->request()->setResponseMethod(RequestProcessor::RESPONSE_JSON);
        $json = UrlCache::getMapJson(_REQINT('id'));
        $this->contents = $json;
    }
    public function Tc() {
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
   
    
    private function normalizeText($text) {
        if (empty($text))
            return '';
        $retVal = trim(preg_replace('~[\*-\+\~\^]{2,}~si' ,' ', stripslashes($text)));
        return $retVal;
    }

    }
?>
