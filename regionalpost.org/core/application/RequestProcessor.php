<?php

    class RequestProcessor {

        const RESPONSE_NORMAL = 'normal';
        const RESPONSE_BLOCK = 'block';
        const RESPONSE_JSON = 'json';
        const RESPONSE_TEXT = 'text';
        const RESPONSE_JS = 'js';


        private $responseMethod = 'normal';


        public $baseAddress = '';
        public $reletiveUrl = '';
        public $fullUrl = '';
        public $langIdentifier;
        public $currencyIdentifier;
        public $AppName = '';
        public $Voider = '';
        public $alias = '';
        public $itemID = 0;
        public $Permission = '';
        public $urlID = 0;
        public $itemType = '';

        public $baseRelativeAddress='';


        public $lastModified = '';
        public $lastModifiedTime = 0;
        public $AllowToCache = false;

        public $meta = array('title'=>'','keys'=>'','descr'=>'','robots'=>true);



        // called before create db instance
        public function Initilize() {

            $suff = trim(BASEDIR);
            if ($suff === '/' || $suff === '\\' || $suff === '.') {
                $suff = '';
            }
            if ($suff !== '') {
                $suff = '/' . trim($suff, '/') . '/';
            } else {
                $suff = '/';
            }

            $https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off');
            if (!$https && !empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $https = (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
            }
            if (!$https && isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443') {
                $https = true;
            }

            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
            // Always end baseAddress with a single trailing slash for <base href>.
            $this->baseAddress = ($https ? 'https://' : 'http://') . $host . $suff;

            $this->langIdentifier = strtolower(trim(isset($_GET['_langid']) ? $_GET['_langid'] : ''));

            if (empty($this->langIdentifier)) {
                $this->baseRelativeAddress = ltrim($suff, '/');
            } else {
                $this->baseRelativeAddress = ltrim($suff, '/') . $this->langIdentifier . '/';
            }

            // set in helpers reload page // here get from url param or from cookie or from session
            if (isset($_GET['_set_currency']) && CultureInfo::isValidCurrency(strtolower(trim($_GET['_set_currency'])))) {
                $this->currencyIdentifier = strtolower(trim($_GET['_set_currency']));
                cookieManager::writeCookie('site_currency', $this->currencyIdentifier);
            } else {
                $this->currencyIdentifier = cookieManager::loadCookie('site_currency');
            }
        }


        public function Handle() {
            $currentUrl = Uri::FindUrl('/');
            if ($this->reletiveUrl==='/') $this->reletiveUrl='';

            $defaultMetas = array( 'title'=>$currentUrl->getProp('meta_title')
                                ,'keys'=>$currentUrl->getProp('meta_keys')
                                ,'descr'=>$currentUrl->getProp('meta_descr'));

            if (isset($_GET['_rwr']) && !empty($_GET['_rwr']) && $_GET['_rwr']!=='index.php') {

                $rwr = CleanUrl($_GET['_rwr']);
                $urlExt = mb_substr($rwr,strrpos($rwr, '.'));
                switch ($urlExt) {
                    case '.html':
                        break;
                    case '.htm':
                        break;
                    case '.json':
                        break;
                    case '.js':
                        $this->responseMethod = RequestProcessor::RESPONSE_JS;
                        break;
                    default:
                        if (substr($rwr, mb_strlen($rwr)-1, 1)!=='/') {
                            $rwr=$rwr.'/';
                        }
                        break;
                }

                $currentUrl = Uri::FindUrl($rwr);
            }

            $this->AppName = $currentUrl->getProp('componenttype');
            $this->Voider = $currentUrl->getProp('voider');
            $this->reletiveUrl = $currentUrl->getProp('url');
            $this->Permission = $currentUrl->getProp('perm');
            $this->alias = $currentUrl->getProp('alias');
            $this->urlID = $currentUrl->getProp('id');
            $this->itemID = $currentUrl->getProp('parent_id');
            $this->lastModified = $currentUrl->getProp('date_modified');
            $this->lastModifiedTime = $currentUrl->getProp('date_modifiedtime');
            $this->AllowToCache = _VARINT($currentUrl->getProp('cache_allow'))>0?true:false;
            $this->itemType = $currentUrl->getProp('itemtype');

            $pageRobots = $currentUrl->getProp('meta_robots');

            // Canonical absolute URL including language prefix (required for Facebook/LinkedIn/Telegram OG).
            $this->fullUrl = $this->buildFullUrl($this->reletiveUrl);

            $robots = Configuration::Load('meta_robots');
            if ($robots==='false') {
                $pageRobots = false;
            }

            $this->meta = array( 'title'=>$currentUrl->getProp('meta_title')
                                ,'keys'=>$currentUrl->getProp('meta_keys')
                                ,'descr'=>$currentUrl->getProp('meta_descr')
                                ,'robots'=>$pageRobots);

            if (empty($this->AppName))
                unset ($this->AppName);


        }

        public function isCached() {
            if (!$this->AllowToCache) return false;
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $this->lastModifiedTime) {
                header('HTTP/1.1 304 Not Modified');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Cache-Control: public, must-revalidate');
                return true;
            }
            return false;
        }


        public function __construct() {

        }

        public function setResponseMethod($value) {
            $this->responseMethod = $value;
        }

        public function IsAjax() {
            $bl = ($_REQUEST['X-Requested-With'] == 'XMLHttpRequest'  || $_REQUEST['response'] == 'block' || $_REQUEST['response'] == 'json' || ($this->responseMethod!==RequestProcessor::RESPONSE_NORMAL && $this->responseMethod!==RequestProcessor::RESPONSE_JS));
            Return $bl;
        }

        public function IsJson() {
            if ($this->responseMethod===RequestProcessor::RESPONSE_JSON)
                return true;
            $acceptType = $_SERVER["HTTP_ACCEPT"];
            $bl = $_REQUEST['response']==='json' || strpos($acceptType, 'application/json')!==false;
            if ($bl===false) {
                $acceptType = strtolower(isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : '');
                $bl = $acceptType==='application/json';
            }
            return $bl;
        }

        public function IsText() {
            return $this->responseMethod === RequestProcessor::RESPONSE_TEXT;
        }

        public function isJS() {
            return $this->responseMethod === RequestProcessor::RESPONSE_JS;
        }

        /**
         * Join base address with a site-relative path without producing double slashes.
         * Accepts paths like "page_files/a.jpg", "/page_files/a.jpg", or "en/articles/x.html".
         */
        public function absoluteUrl($path) {
            $base = rtrim($this->baseAddress, '/');
            $path = str_replace('\\', '/', (string)$path);
            if ($path === '' || $path === '/') {
                return $base . '/';
            }
            if (preg_match('#^https?://#i', $path)) {
                return $path;
            }
            return $base . '/' . ltrim($path, '/');
        }

        /**
         * Build the public canonical URL for a relative CMS path (may or may not include lang).
         */
        public function buildFullUrl($relativeUrl) {
            $base = rtrim($this->baseAddress, '/');
            $lang = trim((string)$this->langIdentifier, '/');
            $rel = trim(str_replace('\\', '/', (string)$relativeUrl), '/');

            if ($rel === '') {
                return $base . '/' . ($lang !== '' ? $lang . '/' : '');
            }

            // Relative URLs from urlcache do not include the language segment.
            if ($lang !== '' && strpos($rel, $lang . '/') !== 0 && $rel !== $lang) {
                return $base . '/' . $lang . '/' . $rel;
            }

            return $base . '/' . $rel;
        }

    }

//<!--Т-->
?>
