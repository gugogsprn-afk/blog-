<?php
/**
 *
 *
 * @author Sight©
 */
class pagesWebPage extends MasterWebPage {


    /**
     *
     * @return \Page
     */
    private function InitPage() {
        $page = new Page(HttpContext::current()->request()->itemID);
        $page->Load();


        $fullUrl = HttpContext::current()->request()->fullUrl;
        $ogTitle = $page->getProp('name');
        $ogDescr = HttpContext::current()->request()->meta['descr'];
        $defaultImage = 'page_files/images/fb_share2.jpg';

        $this->metas =  array(
            'metas'=>HttpContext::current()->request()->meta,
            'metalinks'=>array(),
            'ogtags'=>array(
                array('name'=>'og:title','value'=>$ogTitle),
                array('name'=>'og:site_name','value'=> Configuration::Load('site_company')),
                array('name'=>'og:url','value'=> $fullUrl),
                array('name'=>'og:description','value'=> $ogDescr),
                array('name'=>'og:type','value'=> 'article'),
                array('name'=>'og:locale','value'=> $this->ogLocale()),
             ),
            'canonical'=>$fullUrl
        );

        $filename = $page->getProp('filename');
        $thumbname = $page->getProp('thumbname');
        $index = $page->getProp('intsc') - 1;

        $ogImagePath = '';
        if($index >= 0) {
            $files = $page->getFiles('pages-galery');
            $ff = isset($files[$index]['thumbname']) ? $files[$index]['thumbname'] : '';
            if(!empty($ff)) {
                $ogImagePath = $ff;
            }
        } else {
            if (!empty($filename)) {
                $ogImagePath = $filename;
            } else if (!empty($thumbname)) {
                $ogImagePath = $thumbname;
            }
        }
        if (empty($ogImagePath)) {
            $ogImagePath = $defaultImage;
        }
        $this->appendOgImageTags($ogImagePath);
        $this->appendTwitterCards($ogTitle, $ogDescr, $ogImagePath);


        $currentTitle = Uri::FindUrl('/')->getProp('meta_title');
        if ($this->metas['metas']['title'] == '') {
            $this->metas['metas']['title'] = $currentTitle;
        }

        return $page;
    }

    public function Index() {
        if (HttpContext::current()->request()->itemType === 'category') {
            $this->showCategory();
            return;
        }

        $page = $this->InitPage();

        $block = $page->getProp('block_id');
        $alias = $page->getProp('alias');

        if ($block=='specitems') {
            $this->PodcastItem($page);
            return;
        }
        if ($block=='menu') {
            $this->Menu($page);
            return;
        }

        if ($alias=='fixed-rpb2b') {
            $this->ListInfo($page);
            return;
        }
        if ($alias=='fixed-st') {
            $this->ST($page);
            return;
        }

        $tpl = new Template('simple.inc');
        $tpl->vars['item'] = $page->toArray();

        /*
        if (!empty($_SESSION['debug_mail'])) {
            $tpl->vars['debug_mail'] = $_SESSION['debug_mail'];
        }*/
        // $pp = new Page(null);
        // $pubs = $pp->search(array('block_id'=>'publicat','ncat'=> null), 0, 6, 'tp.date_object DESC, tp.id DESC', false, true);
        // print_r($pubs);
        $configs = Configuration::BulkLoad(array('facebook_sdk','google_plus_sdk','twitter_sdk'));

        $tpl->vars['facebook_sdk'] = ($configs['facebook_sdk']=='true'?'true':'');
        $tpl->vars['google_plus_sdk'] = ($configs['google_plus_sdk']=='true'?'true':'');
        $tpl->vars['twitter_sdk'] = ($configs['twitter_sdk']=='true'?'true':'');
        if (!empty($tpl->vars['facebook_sdk']) ||
                !empty($tpl->vars['google_plus_sdk']) ||
                !empty($tpl->vars['twitter_sdk'])){
            $tpl->vars['social'] = 'true' ;
        }


        $tpl->vars['social'] = $block==='fixed'?'':$tpl->vars['social'] ;
        $tpl->vars['banners'] = $this->banners;

        if ($block==='publicat') {
            $tpl->vars['embed'] = $page->getProp('tags');
            unset($tpl->vars['item']['filename']);
        }


        if ($block=='imageitems') {
            $relIds = $page->getProp('tagsb');
            if (!empty($relIds)) {
                $p = new Page(null);
                $RelItems = $p->search(array('ids'=>$relIds,'block_id'=>'imageitems'), 0, 0, 'tp.date_object desc');

                $tpl->vars['relitems'] = $RelItems['items'];
            }
        }


        $this->contents = $tpl->Render();
    }

    private function showCategory() {
        $cat = new Category(HttpContext::current()->request()->itemID);
        $cat->Load();

        $fullUrl = HttpContext::current()->request()->fullUrl;
        $ogTitle = $cat->getProp('name');
        $ogDescr = HttpContext::current()->request()->meta['descr'];
        $defaultImage = 'page_files/images/fb_share2.jpg';

        $this->metas =  array(
            'metas'=>HttpContext::current()->request()->meta,
            'metalinks'=>array(),
            'ogtags'=>array(
                array('name'=>'og:title','value'=>$ogTitle),
                array('name'=>'og:site_name','value'=> Configuration::Load('site_company')),
                array('name'=>'og:url','value'=> $fullUrl),
                array('name'=>'og:description','value'=> $ogDescr),
                array('name'=>'og:type','value'=> 'website'),
                array('name'=>'og:locale','value'=> $this->ogLocale()),
             ),
            'canonical'=>$fullUrl
        );
        $this->appendOgImageTags($defaultImage);
        $this->appendTwitterCards($ogTitle, $ogDescr, $defaultImage);

        $currentTitle = Uri::FindUrl('/')->getProp('meta_title');
        if ($this->metas['metas']['title'] == '') {
            $this->metas['metas']['title'] = $currentTitle;
        }

        $tpl = new Template('collection.inc');

        // $cid = intval(HttpContext::current()->request()->itemID);
        // if (intval($cat->getProp('parent_id'))>0)
        // {
        //     $cid = intval($cat->getProp('parent_id'));
        // }

        // $cat = new Category(null);
        // $tpl->vars['subcat'] = $cat->getList('tc.parent_id='.$cid);

        $pageSize = Configuration::Load('items_onpage');
        $pageNum = max(_REQINT('page'),1);
        $startIndex = ($pageNum-1) * _VARINT($pageSize);

        $p = new Page(null);
        $items =$p->search(array('cat'=>$cat->ID,'block_id'=>'imageitems'), $startIndex, $pageSize, 'tp.date_object desc, tp.id desc');
        $tpl->vars['items'] = $items['items'];
        $tpl->vars['pagelist'] = Template::ListPages('<a href="' . HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl.'%param%" data-page="%page%">%link%</a>', $items['total'], $startIndex, $pageSize);

        $this->metas['metalinks'] = Template::ListPageMetas(HttpContext::current()->request()->reletiveUrl, $items['total'], $startIndex, $pageSize);

        $tpl->vars['curview'] = HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl;
        $tpl->vars['item'] = $cat->toArray();
        $tpl->vars['banners'] = $this->banners;
        $this->contents = $tpl->Render();

    }

    /**
     *
     * @param Page $page
     */
    private function childIndex($page) {
        $tpl = new Template('simplechild.inc');

        $tpl->vars['item'] = $page->toArray();

        $pageParent = new Page($page->getProp('parent_id'));
        $pageParent->load();

        $configs = Configuration::BulkLoad(array('facebook_sdk','google_plus_sdk','twitter_sdk'));

        $tpl->vars['facebook_sdk'] = ($configs['facebook_sdk']=='true'?'true':'');
        $tpl->vars['google_plus_sdk'] = ($configs['google_plus_sdk']=='true'?'true':'');
        $tpl->vars['twitter_sdk'] = ($configs['twitter_sdk']=='true'?'true':'');
        $tpl->vars['linkedin_sdk'] = ($configs['linkedin_sdk']=='true'?'true':'');
        if (!empty($tpl->vars['facebook_sdk']) ||
                !empty($tpl->vars['google_plus_sdk']) ||
                !empty($tpl->vars['twitter_sdk'])) {
            $tpl->vars['social'] = 'true' ;
        }

        $tpl->vars['social'] = $page->getProp('block_id')==='fixed'?'':$tpl->vars['social'] ;
        $tpl->vars['parent'] = $pageParent->toArray();
        $tpl->vars['banners'] = $this->banners;
        $this->contents = $tpl->Render();
    }

    public function Menu() {
        $page = $this->InitPage();

        $tpl = new Template('about.inc');
        $tpl->vars['item'] = $page->toArray();

        $mediaKit = HttpContext::current()->request()->absoluteUrl('page_files/media-kit.pdf');

        if($mediaKit) {
            $tpl->vars['media_kit'] = HttpContext::current()->request()->absoluteUrl('page_files/media-kit.pdf');
        }

        $this->contents = $tpl->Render();
    }
    public function Podcasts() {
        $page = $this->InitPage();

        $displayStyle = 'grid';


        $tpl = new Template('podcast.inc');


        $pageSize = Configuration::Load('items_onpage');
        if ($displayStyle=='list') {
            $pageSize = round($pageSize * 2);
        }
        $pageNum = max(_REQINT('page'),1);
        $startIndex = ($pageNum-1) * _VARINT($pageSize);

        $pageColl = new Page(null);
        $pageColl->setProp('tp.block_id', 'specitems');
        $collection = $pageColl->Collection(true);
        $collection->ordering = 'tp.pos';
        $collection->Load(false,$startIndex,$pageSize,true);
        $totalCount = $collection->totalCount();

        $this->metas['metalinks'] = Template::ListPageMetas(HttpContext::current()->request()->reletiveUrl, $totalCount, $startIndex, $pageSize);

        $tpl->vars['items'] = $collection->toArray();
        $tpl->vars['pagelist'] = Template::ListPages('<a href="' . HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl.'%param%" data-page="%page%">%link%</a>', $totalCount, $startIndex, $pageSize);
        $tpl->vars['curview'] = HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl;
        $tpl->vars['displaystyle'] = $displayStyle;
        $tpl->vars['item'] = $page->toArray();
        $tpl->vars['banners'] = $this->banners;


        $this->contents = $tpl->Render();
    }

     public function PodcastItem($page) {

        $displayStyle = 'grid';


        $tpl = new Template('podcast_items.inc');


        $pageSize = Configuration::Load('items_onpage');
        if ($displayStyle=='list') {
            $pageSize = round($pageSize * 2);
        }
        $pageNum = max(_REQINT('page'),1);
        $startIndex = ($pageNum-1) * _VARINT($pageSize);

        $pageColl = new Page(null);
        $pageColl->setProp('tp.parent_id', $page->ID);
        $collection = $pageColl->Collection(true);
        $collection->ordering = 'tp.pos';
        $collection->Load(false,$startIndex,$pageSize,true);
        $totalCount = $collection->totalCount();

        $this->metas['metalinks'] = Template::ListPageMetas(HttpContext::current()->request()->reletiveUrl, $totalCount, $startIndex, $pageSize);

        $tpl->vars['items'] = $collection->toArray();
        $tpl->vars['pagelist'] = Template::ListPages('<a href="' . HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl.'%param%" data-page="%page%">%link%</a>', $totalCount, $startIndex, $pageSize);
        $tpl->vars['curview'] = HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl;
        $tpl->vars['displaystyle'] = $displayStyle;
        $tpl->vars['item'] = $page->toArray();
        $tpl->vars['banners'] = $this->banners;


        $this->contents = $tpl->Render();
    }
     public function ListInfo($page) {

        $tpl = new Template('flatitems.inc');

        $tpl->vars['item'] = $page->toArray();

        $tpl->vars['flatitems'] = Page::LoadBlockCollection('flatitems', true, false);

        $this->contents = $tpl->Render();
    }
     public function ST($page) {

        $tpl = new Template('st.inc');

        $tpl->vars['item'] = $page->toArray();

        $this->contents = $tpl->Render();
    }



    public function Collection() {
        $page = $this->InitPage();

        $displayStyle = 'grid';

        /*
        if (isset($_SESSION['displaystyle'])) {
            if ($_SESSION['displaystyle']==='list') {
                $displayStyle = 'list';
            }
        }
        if (isset($_REQUEST['view'])) {
            if (_REQUEST('view')==='list') {
                $displayStyle = 'list';
            } else {
                $displayStyle = 'grid';
            }
        }

        $_SESSION['displaystyle'] = $displayStyle;
        */

        $ordering = 'tp.pos';
        $orderings = array(
            'imageitems'=>'tp.date_object DESC',
            'publicat'=>'tp.date_object DESC'
        );
        /*
        $tpls = array(
            'menusub'=>'staff.inc'
        );*/
        $tplName = 'collection.inc';

        if (array_key_exists($page->getProp('block_id'), $orderings)) {
            $ordering = $ordering[$page->getProp('block_id')];
        }

        /*
        if (array_key_exists($page->getProp('block_id'), $tpls)) {
            $tplName = $tpls[$page->getProp('block_id')];
        }*/
        /*
        if ($page->getProp('block_id')=='menusub') {

        }*/

        if ($page->getProp('id') == 6) {
            $tpl = new Template('collectionpub.inc');
        }
        else {
            $tpl = new Template($tplName);
        }



        $pageSize = Configuration::Load('items_onpage');
        if ($displayStyle=='list') {
            $pageSize = round($pageSize * 2);
        }
        $pageNum = max(_REQINT('page'),1);
        $startIndex = ($pageNum-1) * _VARINT($pageSize);

        $pageColl = new Page(null);
        $pageColl->setProp('tp.parent_id', $page->ID);
        $collection = $pageColl->Collection(true);
        $collection->ordering = $ordering;
        $collection->Load(false,$startIndex,$pageSize,true);
        $totalCount = $collection->totalCount();

        $this->metas['metalinks'] = Template::ListPageMetas(HttpContext::current()->request()->reletiveUrl, $totalCount, $startIndex, $pageSize);

        $tpl->vars['items'] = $collection->toArray();
        $tpl->vars['pagelist'] = Template::ListPages('<a href="' . HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl.'%param%" data-page="%page%">%link%</a>', $totalCount, $startIndex, $pageSize);
        $tpl->vars['curview'] = HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl;
        $tpl->vars['displaystyle'] = $displayStyle;
        $tpl->vars['item'] = $page->toArray();
        $tpl->vars['banners'] = $this->banners;
        $this->contents = $tpl->Render();
    }



    public function Search() {
        $page = $this->InitPage();

        $pageSize = Configuration::Load('items_onpage');
        $pageNum = max(_REQINT('page'),1);
        $startIndex = ($pageNum-1) * _VARINT($pageSize);

        $word = clearInput(_REQUEST('srch'),0,50);

        $tpl = new Template('search_pages.inc');

        if (mb_strlen($word)>2) {
            $p = new Page(null);
            $items =$p->search(array('word'=>$word,'block_id'=>'imageitems'), $startIndex, $pageSize, 'tp.date_object desc');
            $tpl->vars['items'] = $items['items'];
            $tpl->vars['pagelist'] = Template::ListPages('<a href="' . HttpContext::current()->culture()->LanguagePath().HttpContext::current()->request()->reletiveUrl.'%param%&srch='.urlencode($word).'" data-page="%page%">%link%</a>', $items['total'], $startIndex, $pageSize);

        }

        $tpl->vars['word'] = htmlspecialchars($word);
        $tpl->vars['item'] = $page->toArray();
        $tpl->vars['banners'] = $this->banners;
        $this->contents = $tpl->Render();
    }

}
?>
