<?php

class MasterWebPage extends WebPageBase {

    protected $Styles = array();
    protected $Scripts = array();


    protected $pageParams =array();
    protected $pageUrl='';



    protected $metas = array();

    protected $banners = array();
    /**
     *
     * @var Template
     */
    protected $MainTpl = null;
    protected $contents = '';

    protected $isFirstRun = false;

    protected $headersHandled = false;

    protected $MainMenu = null;
    protected $rootPage = null;


    protected $sliderVisible = false;

    public function __construct() {
        parent::__construct();
    }

    public function Initilize() {
        $this->MainTpl = new Template('main.inc');

        $params = array();
        foreach ($this->pageParams as $key) {
            $value = _REQUEST($key);
            $params[$key] = $value;
        }
        $this->pageUrl = http_build_query($params);
        if (empty($_SESSION['notfirstrun'])) {
            $_SESSION['notfirstrun'] = 1;
            $this->isFirstRun = true;
        }
        if (!(HttpContext::current()->request()->IsAjax() ||
                HttpContext::current()->request()->IsJson() ||
                HttpContext::current()->request()->isJS())) {
            $selID = 0;
            if (HttpContext::current()->request()->itemType === 'category') {
                $selID = intval(HttpContext::current()->request()->itemID);
            }
            $cat = new Category(null);
            $this->MainMenu = $cat->getList('tc.parent_id=0');

            // $podcastPage = Page::loadFixed('fixed-podcast')->toArray();
            $archivePage = Page::loadFixed('fixed-publicat')->toArray();

            // $this->MainMenu[] = $podcastPage;
            $this->MainMenu[] = $archivePage;

            if ($selID>0) {
                $this->MainMenu[$selID]['selected'] = 'selected';
            }
            /*
            $p = new Page(null);
            $this->MainMenu = $p->loadTree('menu', 'menusub', $selID);
            $this->MainMenu = Page::LoadBlockCollection('menu',true, true);
            if (HttpContext::current()->request()->itemType === 'pages' && array_key_exists(HttpContext::current()->request()->itemID, $this->MainMenu)) {
                $this->MainMenu[HttpContext::current()->request()->itemID]['selected'] = 'selected';
            }*/
            $this->banners = Banner::Collection(HttpContext::current()->request()->urlID);
            $configs = Configuration::BulkLoad(array('fbapp','fbadmins','google_id'));
            $this->MainTpl->vars['fbapp'] = $configs['fbapp'];
            $this->MainTpl->vars['fbadmins'] = $configs['fbadmins'];
        }
        $siteName = Configuration::Load('site_company');
        $pageTitle = HttpContext::current()->request()->meta['title'];
        if (empty($pageTitle)) {
            $pageTitle = $siteName ? $siteName : 'Regional Post';
        }
        $pageDescr = HttpContext::current()->request()->meta['descr'];
        $fullUrl = HttpContext::current()->request()->fullUrl;
        $defaultImage = 'page_files/images/fb_share2.jpg';

        $this->metas = array(
            'metas'=>HttpContext::current()->request()->meta,
            'metalinks'=>array(),
            'ogtags'=>array(
                array('name'=>'og:title','value'=>$pageTitle),
                array('name'=>'og:site_name','value'=> $siteName),
                array('name'=>'og:url','value'=> $fullUrl),
                array('name'=>'og:description','value'=> $pageDescr),
                array('name'=>'og:type','value'=> 'website'),
                array('name'=>'og:locale','value'=> $this->ogLocale()),
            ),
            'canonical'=>$fullUrl
        );
        $this->appendDefaultOgImage($defaultImage);
        $this->appendTwitterCards($pageTitle, $pageDescr, $defaultImage);

    }

        /**
    *
    * @return \Template
    */
    protected function getSlider() {
        $tpl = new Template('slider.inc');
        $tpl->vars['slidespeed'] = Configuration::Load('slide_speed');
        $slides = Slide::Collection();

        if (count($slides)>0) {
            if (count($slides)==1) {
                $tpl->vars['slide'] = $slides[0];
            } else {
                $tpl->vars['slides'] = $slides;
            }
            $img =  Configuration::Load('image_slide');
            $tpl->vars['slideheight'] =$img['h'];
            $tpl->vars['anyslide'] = 'true';

        }

        $tpl->vars['sitename'] = Configuration::Load('site_company');
        return $tpl;
    }


    public function Index() {
        $confs = Configuration::BulkLoad(array('items_onhome','items_like','items_onmiddle'));

        $tpl = null;

        if (_REQINT('loadpart')==1) {
            $tpl = new Template('main_page_partial.inc');

        }
        else {
            $tpl = new Template('main_page.inc');
        }


        $tpl->vars['mainpage'] = Page::loadFixed('fixed-root')->toArray();

        $rootPage = Page::loadFixed('fixed-root')->toArray();
        $fixIds = explode(',', $rootPage['tags']);

        $startIndex = _REQINT('page')*14;
        if ($startIndex<0) {
            $startIndex = 0;
        }

        $pageIdsCleaned = int_Array_KeepIndexes($fixIds);
        $pp = new Page(null);
        $articles = $pp->search(
            array(
                'block_id'=>'imageitems',
            ), $startIndex, 9, 'tp.date_object DESC, tp.id DESC', false, true);


        $pubs = $pp->search(array('block_id'=>'publicat','ncat'=> null), 0, 0, 'tp.date_object DESC, tp.id DESC', false, true);

        $articles =  array_values($articles);

        $p = new Page(null);
        $fixedPages = $p->search(
            array(
                'block_id'=>'imageitems',
                'ids'=>  implode(',', $pageIdsCleaned)
            ), 0, 0, 'tp.id', false, true);

        if (strlen($rootPage['tags']) > 0) {
            $tpl->vars['slides'] = $fixedPages;
        }

        $tpl->vars['articles'] = $articles;
        $tpl->vars['pubs'] = $pubs;

        $c = new Category(481);
        $c->Load();
        $tpl->vars['brief'] = $c->toArray();

        $p = new Page(null);
        $items =$p->search(array('cat'=>$c->ID,'block_id'=>'imageitems'), 0, intval($confs['items_onhome']), 'tp.date_object desc');
        $tpl->vars['news'] = $items['items'];

        $slider = $this->getSlider();
        if ($slider!==null) {
            $tpl->vars['slider'] = $slider->Render();
        }

        $tpl->vars['th'] = isset($_SESSION['R_THUMBPRINT']) ? md5($_SESSION['R_THUMBPRINT']) : '';
        $this->contents= $tpl->Render();
    }


    /**
    *
    * @return \Template
    */
    protected function getAside() {
        $tpl = new Template('aside.inc');
        $tpl->vars['banners'] = $this->banners;

        return $tpl;
    }


    /**
    *
    * @return \Template
    */
    protected function getHeader() {
        $configs = Configuration::BulkLoad(array('site_company','phone'));
        $tpl = new Template('header.inc');

        $langList = HttpContext::current()->culture()->LanguageList();
        $tpl->vars['currlang'] = $langList[HttpContext::current()->culture()->Language()];
        unset($langList[HttpContext::current()->culture()->Language()]);
        foreach ($langList as $key => $value) {
            $langList[$key]['url'] = $value['id'].'/'.(HttpContext::current()->request()->reletiveUrl=='/'?'':HttpContext::current()->request()->reletiveUrl);
        }

        $tpl->vars['menus'] = $this->MainMenu;

        $tpl->vars['sitename'] = $configs['site_company'];
        $tpl->vars['socs'] =$this->getSocs();
        $tpl->vars['pub'] = Page::loadFixed('fixed-publicat')->toArray();
        $tpl->vars['rpb2b'] = Page::loadFixed('fixed-rpb2b')->toArray();

        $tpl->vars['langlist'] = $langList;
        $tpl->vars['banners'] = $this->banners;


        if (HttpContext::current()->request()->itemType === 'category') {
            $curCat = new Category(HttpContext::current()->request()->itemID);
            $curCat->Load();
            $cid = intval(HttpContext::current()->request()->itemID);
            if (intval($curCat->getProp('parent_id'))>0)
            {
                $cid = intval($curCat->getProp('parent_id'));
            }

            $cat = new Category(null);
            $tpl->vars['subcat'] = $cat->getList('tc.parent_id='.$cid);
        }

        return $tpl;
    }

    /**
     *
     * @return \Template
     */
    protected function getFooter() {
        $tpl = new Template('footer.inc');

        $SiteInfo = Configuration::BulkLoad(array('mail_support','site_company','phone','address'));


        $tpl->vars['socs'] =$this->getSocs();

        $tpl->vars['rpb2b'] = Page::loadFixed('fixed-rpb2b')->toArray();
        $tpl->vars['st'] = Page::loadFixed('fixed-st')->toArray();

        $mediaKitRel = 'page_files/media-kit.pdf';
        $mediaKitFs = rtrim(ROOTDIR, '/').'/'.$mediaKitRel;
        if (is_file($mediaKitFs)) {
            $tpl->vars['media_kit'] = HttpContext::current()->request()->absoluteUrl($mediaKitRel);
        }

        $year = date('Y');
        $yearInterval = (intval($year)>2017?'2017 - '.$year:$year);
        $tpl->vars['years'] = $yearInterval;
        $tpl->vars['email'] = $SiteInfo['mail_support'];
        $tpl->vars['phone'] = $SiteInfo['phone'];
        $tpl->vars['address'] = nl2br($SiteInfo['address']);
        $tpl->vars['site_company'] =$SiteInfo['site_company'];
        $tpl->vars['page'] = $this->rootPage;
        $tpl->vars['fmenu'] = Page::LoadBlockCollection('menu',true, true);
        $tpl->vars['contacts'] = $this->MainMenu[2];
        $tpl->vars['contacts']['descr'] = isset($tpl->vars['contacts']['descr']) ? nl2br($tpl->vars['contacts']['descr']) : '';
        return $tpl;
    }

    protected function getMetas() {
        return $this->metas;
    }

    protected function getSocs() {
        $socOrder = array(
            'soc_email'=>0,
            'soc_fb'=>1,
            'soc_twiter'=>2,
            'soc_google'=>3,
            'soc_youtube'=>4,
            'soc_instagram'=>5,
            'soc_li'=>6,
            'soc_pinterest'=>7,
            'soc_skype'=>8,
            'soc_vimeo'=>9,
            'soc_rss'=>10
        );

        $socConfig = Configuration::BulkLoad(array('soc_email','soc_fb','soc_twiter','soc_google','soc_youtube','soc_instagram','soc_li','soc_pinterest','soc_skype','soc_vimeo','soc_rss'));
        $sucLinks = array();
        foreach ($socConfig as $key => $value) {
            if (empty($value))
                continue;
            $sucLinks[intval($socOrder[$key])] = array(
                'name'=>$key,
                'url'=>$value
            );
        }
        ksort($sucLinks);
        return $sucLinks;
    }

    protected function ogLocale() {
        $lang = HttpContext::current()->culture()->Language();
        if ($lang === 'fr') {
            return 'fr_FR';
        }
        if ($lang === 'ru') {
            return 'ru_RU';
        }
        if ($lang === 'hy') {
            return 'hy_AM';
        }
        return 'en_US';
    }

    /**
     * Default share image for pages without a featured image.
     */
    protected function appendDefaultOgImage($relativePath) {
        $this->appendOgImageTags($relativePath);
    }

    protected function appendTwitterCards($title, $description, $imageRelativeOrAbsolute) {
        $imageUrl = $imageRelativeOrAbsolute;
        // Prefer the Facebook-safe og:image already emitted (baseline JPEG / resized).
        if (!empty($this->metas['ogtags'])) {
            for ($i = count($this->metas['ogtags']) - 1; $i >= 0; $i--) {
                if (isset($this->metas['ogtags'][$i]['name']) && $this->metas['ogtags'][$i]['name'] === 'og:image') {
                    $imageUrl = $this->metas['ogtags'][$i]['value'];
                    break;
                }
            }
        }
        if (!preg_match('#^https?://#i', (string)$imageUrl)) {
            $imageUrl = HttpContext::current()->request()->absoluteUrl($imageUrl);
        }
        $this->metas['ogtags'][] = array('name'=>'twitter:card', 'value'=>'summary_large_image');
        $this->metas['ogtags'][] = array('name'=>'twitter:title', 'value'=>$title);
        $this->metas['ogtags'][] = array('name'=>'twitter:description', 'value'=>$description);
        $this->metas['ogtags'][] = array('name'=>'twitter:image', 'value'=>$imageUrl);
    }

    /**
     * Emit Facebook-safe Open Graph image tags.
     * Progressive/interlaced images and huge PNGs often render blank in Facebook previews.
     */
    protected function appendOgImageTags($relativePath) {
        if (empty($relativePath)) {
            return;
        }

        $relativePath = str_replace('\\', '/', $relativePath);
        if (preg_match('#^https?://#i', $relativePath)) {
            $this->metas['ogtags'][] = array('name'=>'og:image', 'value'=> $relativePath);
            $this->metas['ogtags'][] = array('name'=>'og:image:secure_url', 'value'=> $relativePath);
            return;
        }

        $diskPath = ROOTDIR . ltrim($relativePath, '/');
        if (!is_file($diskPath)) {
            return;
        }

        $shareRelative = $relativePath;
        $ext = strtolower(pathinfo($diskPath, PATHINFO_EXTENSION));
        $needsBaseline = in_array($ext, array('png', 'gif'), true) || $this->isProgressiveJpeg($diskPath);
        $tooLarge = (@filesize($diskPath) > 900000);

        if ($needsBaseline || $tooLarge) {
            $shareRelative = preg_replace('/\.(png|gif|jpe?g)$/i', '_fbshare.jpg', $relativePath);
            $shareDisk = ROOTDIR . ltrim($shareRelative, '/');
            if (!is_file($shareDisk) || filemtime($shareDisk) < filemtime($diskPath)) {
                if (!$this->createFbShareJpeg($diskPath, $shareDisk)) {
                    $shareRelative = $relativePath;
                    $shareDisk = $diskPath;
                }
            }
        } else {
            $shareDisk = $diskPath;
        }

        // Cache-bust so Telegram/Facebook re-fetch after social image is replaced in admin.
        $ver = @filemtime($shareDisk);
        if (!$ver) { $ver = time(); }
        $imageUrl = HttpContext::current()->request()->absoluteUrl($shareRelative) . '?v=' . $ver;
        $this->metas['ogtags'][] = array('name'=>'og:image', 'value'=> $imageUrl);
        $this->metas['ogtags'][] = array('name'=>'og:image:secure_url', 'value'=> $imageUrl);

        $info = @getimagesize($shareDisk);
        if (is_array($info)) {
            if (!empty($info[0]) && !empty($info[1])) {
                $this->metas['ogtags'][] = array('name'=>'og:image:width', 'value'=> (string)$info[0]);
                $this->metas['ogtags'][] = array('name'=>'og:image:height', 'value'=> (string)$info[1]);
            }
            if (!empty($info['mime'])) {
                $this->metas['ogtags'][] = array('name'=>'og:image:type', 'value'=> $info['mime']);
            }
        }
    }

    protected function isProgressiveJpeg($path) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext !== 'jpg' && $ext !== 'jpeg') {
            return false;
        }
        $fh = @fopen($path, 'rb');
        if (!$fh) {
            return false;
        }
        $data = fread($fh, 65536);
        fclose($fh);
        if ($data === false || $data === '') {
            return false;
        }
        // SOF2 (0xFFC2) indicates a progressive JPEG.
        return (strpos($data, "\xFF\xC2") !== false);
    }

    protected function createFbShareJpeg($sourcePath, $destPath) {
        $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'png':
                $src = @imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $src = @imagecreatefromgif($sourcePath);
                break;
            case 'jpg':
            case 'jpeg':
                $src = @imagecreatefromjpeg($sourcePath);
                break;
            default:
                return false;
        }
        if (!$src) {
            return false;
        }

        $w = imagesx($src);
        $h = imagesy($src);
        // Keep OG images within a crawler-friendly size (recommended ~1200px wide).
        $maxW = 1200;
        if ($w > $maxW) {
            $nw = $maxW;
            $nh = max(1, (int)round($h * ($maxW / $w)));
            $resized = imagecreatetruecolor($nw, $nh);
            if ($resized) {
                $white = imagecolorallocate($resized, 255, 255, 255);
                imagefilledrectangle($resized, 0, 0, $nw, $nh, $white);
                imagecopyresampled($resized, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
                imagedestroy($src);
                $src = $resized;
                $w = $nw;
                $h = $nh;
            }
        }

        $dst = imagecreatetruecolor($w, $h);
        if (!$dst) {
            imagedestroy($src);
            return false;
        }

        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $w, $h, $white);
        imagecopy($dst, $src, 0, 0, 0, 0, $w, $h);

        $dir = dirname($destPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        imageinterlace($dst, 0);
        $ok = imagejpeg($dst, $destPath, 82);
        imagedestroy($src);
        imagedestroy($dst);
        return (bool)$ok;
    }


    private function setHeaders() {
        $responseBlock = false;
        $allowCache = HttpContext::current()->request()->AllowToCache;

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        if (HttpContext::current()->request()->isJS()) {
            $responseBlock = true;
            header('Content-type: application/javascript; charset=utf-8');
        } else if (HttpContext::current()->request()->IsJson()) {
            $responseBlock = true;
            $allowCache = false;
            header('Content-type: application/json');
        } else if (HttpContext::current()->request()->IsAjax ()) {
            $responseBlock = true;
            $allowCache = false;
            header('Content-Type: text/html; charset=utf-8');
        } else if (HttpContext::current()->request()->IsText()) {
            header('Content-Type: text/plain; charset=utf-8');
        } else {
            header('Content-Type: text/html; charset=utf-8');
        }

        if ($allowCache) {
            header('Cache-Control: public, must-revalidate');
            header('Last-Modified: '.HttpContext::current()->request()->lastModified.' GMT');
        } else {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            header_remove('Last-Modified');
        }

        return $responseBlock;
    }



    public function Display() {
        $responseBlock = $this->setHeaders();

        if ($responseBlock===true) {
            if ($this->contents===true) {
                echo '{"result":"ok"}';
            } else {
                echo $this->contents;
            }
            return;
        }

        $header = $this->getHeader();
        if ($header!==null) {
            $this->MainTpl->vars['header'] = $header->Render();
        }

        $footer = $this->getFooter();
        if ($footer!==null) {
            $this->MainTpl->vars['footer'] = $footer->Render();
        }
        $aside = $this->getAside();
        if ($aside!==null) {
            $this->MainTpl->vars['raside'] = $aside->Render();
        }

        $metaProps = $this->metas;

        $this->MainTpl->vars['meta'] = $metaProps['metas'];
        $rb = $this->MainTpl->vars['meta']['robots'];
        $this->MainTpl->vars['meta']['robots'] = ($rb==true?'INDEX, FOLLOW':'NOINDEX, NOFOLLOW');


        $configs = Configuration::BulkLoad(array('facebook_sdk','google_plus_sdk','twitter_sdk','google_anal'));

        $this->MainTpl->vars['facebook_sdk'] = ($configs['facebook_sdk']=='true'?'true':'');
        $this->MainTpl->vars['google_plus_sdk'] = ($configs['google_plus_sdk']=='true'?'true':'');
        $this->MainTpl->vars['twitter_sdk'] = ($configs['twitter_sdk']=='true'?'true':'');
        $this->MainTpl->vars['google_anal'] = $configs['google_anal'];


        if (!isset($_SESSION['cookiesEnabled']))
            $this->isFirstRun = true;

        $fontStyles = array(
            'hy'=>'',
            'ru'=>'',
            'en'=>''
        );
        $this->MainTpl->vars['fontlang'] =$fontStyles[HttpContext::current()->culture()->Language()];
        $this->MainTpl->vars['culture']= HttpContext::current()->culture()->Culture();
        $this->MainTpl->vars['firstload'] = ($this->isFirstRun===true?'true':'');
        $this->MainTpl->vars['styles']=$this->Styles;
        $this->MainTpl->vars['scripts']=$this->Scripts;
        $this->MainTpl->vars['contents']=$this->contents;
        $this->MainTpl->vars['metalinks'] = $metaProps['metalinks'];
        $this->MainTpl->vars['ogtags'] = $metaProps['ogtags'];
        $this->MainTpl->vars['canonical'] =(!empty($metaProps['canonical'])?$metaProps['canonical']:'');
        $this->MainTpl->vars['rand'] = rand();
        $this->MainTpl->vars['identified'] = HttpContext::current()->Identified()?'true':'';
        $this->MainTpl->vars['banners'] = $this->banners;

        echo $this->MainTpl->Render();

    }



    }

?>
