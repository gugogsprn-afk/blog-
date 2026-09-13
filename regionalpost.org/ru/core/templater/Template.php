<?php
    
class Template {
    
    private $tplFolder = 'tpl/';
    private $path = '';
    
    public $vars = null;
    
    
    public function __construct($templatePath) {
        $this->setTemplate($templatePath);
    }
    
    public function setTemplate($templatePath) {
        $this->path = $templatePath;
        if (!file_exists(CURPATH.$this->tplFolder.$this->path)) throw new ETemplateError('['.$templatePath.'] Template not found');
    }
    
   
    public function Render() {
        $tpl = file_get_contents(CURPATH.$this->tplFolder.$this->path);
        if ($tpl===false) throw new ETemplateError('['.$this->path.'] Error loading template');
        $parsed = '';
        try {
            $parser = new TemplateParser();
            $this->vars['dict'] = Dictionary::loadAll();
            $this->vars['baserelative'] = HttpContext::current()->request()->baseRelativeAddress;
            $this->vars['baseaddress'] = HttpContext::current()->request()->baseAddress;
            $this->vars['curlang'] = HttpContext::current()->culture()->Language();
            $this->vars['curlangp'] = HttpContext::current()->culture()->LanguagePath();
            $this->vars['culture'] = HttpContext::current()->culture()->Culture();
            $this->vars['langname'] = HttpContext::current()->culture()->Name();
            $this->vars['baseaddresslang'] = HttpContext::current()->request()->baseAddress.HttpContext::current()->culture()->LanguagePath();
            // Nested /ru and /fr apps already include the language folder in baseAddress.
            if (HttpContext::current()->culture()->LanguagePath() === '') {
                $this->vars['baseaddresslang'] = HttpContext::current()->request()->baseAddress;
            }
            $this->vars['currenturl'] = HttpContext::current()->request()->reletiveUrl;
            $this->vars['fullurl'] = HttpContext::current()->request()->fullUrl;
            $this->vars['currency'] = HttpContext::current()->culture()->CurrencyShort();
            $this->vars['langs'] = HttpContext::current()->culture()->LanguageArr();
            /*
            $this->vars['_cur_format'] = HttpContext::current()->culture()->CurrencyFormat();
            $this->vars['_cur_decimals'] = HttpContext::current()->culture()->CurrencyDecimals();
             * 
             */
            $parser->setParams($tpl, $this->vars);
            $parser->ParseTpl();
            $parser->CleanTpl();
            //$parser->CleanUrls();
            $parsed = $parser->getTemplate();
        } catch (ETemplateError $exc) {
            throw new ETemplateError('Template error  : ['.$this->path.'] '.$exc->getMessage());
        } catch (Exception $exc) {
            throw new ETemplateError('Template error  : ['.$this->path.'] '.$exc->getMessage());
        }

        return $parsed;
    }
    
    
    public static function TplExists($name) {
        return file_exists(CURPATH.'tpl/'.$name);
    }
    
    public static function ListPages($href, $total, $os, $rpp, $limit = 20,$separator='?') {
            $href = mb_str_replace('&', '&amp;', $href);
            $page = floor($os / $rpp) + 1;
            $pages = ceil($total / $rpp);
            $_start = max(0, $page - max(ceil($limit / 2), $page - $pages + $limit)) + 1;
            $_finish = min($pages, $_start + $limit - 1);
            if ($qs && $qs[0] != "?") {
                $qs = "?" . $qs;
            }
            if ($rpp < $total) {
                $i = $_start;
                $ret = '<ul class="pagelisting">';
                for (; $i <= $_finish; ++$i) {
                    if ($i != $page) {
                        $ret .= '<li class="pagenum">' . str_replace(array("%os%", "%link%","%page%","%param%"), array(( $i - 1 ) * $rpp, $i, $i,($i<=1?'':$separator.'page='.$i)), $href) . "</li>";
                    } else {
                        $ret .= '<li class="curpagenum"><span>' . $i . "</span></li>";
                    }
                }
                $ret .= "</ul>";
            }
            return $ret;
        }

    
    
    public static function ListPageMetas($href,$total,$os,$onpage, $separator = '?') {
        $metas = array();
        $href = mb_str_replace('&', '&amp;', $href);
        if ($href==='/') $href='';
        $CurPage = floor($os / $onpage) + 1;
        $pages = ceil($total / $onpage);
        if ($CurPage>1) {
            $os = (( $CurPage - 2 ) * $onpage);
            $link = HttpContext::current()->request()->absoluteUrl(HttpContext::current()->culture()->LanguagePath().($os>0?$href.$separator.'&amp;page='.($CurPage-1):$href));
            $meta = array(
                'rel'=>'prev',
                'href'=>$link
            );
            $metas[]=$meta;
        }
        if ($CurPage<$pages) {
            $meta = array(
                'rel'=>'next',
                'href'=>HttpContext::current()->request()->absoluteUrl(HttpContext::current()->culture()->LanguagePath().$href.$separator.'&amp;page='.($CurPage+1))
            );
            $metas[]=$meta;
        }
        
        return $metas;
    }
}
?>