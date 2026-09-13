<?php

    class filebrowserWebPage extends MasterWebPage {

    protected $pageParams =array("show");
    protected $Styles = array('plugins/elfinder/css/elfinder.min.css','plugins/elfinder/css/theme.css');
 
    private $fileTypes = array('all'=>'','images'=>'Images','animation'=>'Animations');
    
    public function Index() {
        $type = _REQUEST('type');
        if (_REQUEST('act')=='connect') {
            $this->Connect();
            return;
        }
        
        if (empty($type)) $type='all';
        if (!array_key_exists($type, $this->fileTypes)) 
            throw new EPageError(EPageError::PAGE_NOT_FOUND);
        
        $tpl = new Template('filebrowser/main.inc');
        $tpl->vars['type'] = $type;
        $tpl->vars['standalone'] = (_REQINT('standalone')===1?'true':'');
        $tpl->vars['current']['pageparams'] =$this->pageUrl;
        $tpl->vars['current']['appname']=  _APPNAME();
        $tpl->vars['current']['queryparams'] = $this->QueryParams;
        $tpl->vars['current']['title'] = $this->fileTypes[$type];
        $this->contents = $tpl->Render();
    }
    
    
    private function Connect() {
        $opts = array(
	// 'debug' => true,
                /*
                'bind' => array(
                    'upload' => array($this, 'doResize')
                ),*/
                'roots' => array(
                        array(
                                'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                                'path'          => ROOTDIR.'page_files/',         // path to files (REQUIRED)
                                'URL'           => dirname($_SERVER['PHP_SELF']) . '/../page_files/', // URL to files (REQUIRED)
                                'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                        )
                )
        );
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }
    
    
    /**
     * Upload/resize callback catcher, resizes image to 320x240px/240x320px respectively, keeps ratio
     *
     * @param  string   $cmd       command name
     * @param  array    $result    command result
     * @param  array    $args      command arguments from client
     * @param  object   $elfinder  elFinder instance
     * @return true     Forces elFinder to sync all events
     * */
    public function doResize($cmd, $result, $args, $elfinder) {
        $files = $result['added'];
        foreach ($files as $file) {
            $arg = array(
                'target' => $file['hash'],
                'width' => 320,
                'height' => 320,
                'x' => 0,
                'y' => 0,
                'mode' => 'propresize',
                'degree' => 0
            );
            $elfinder->exec('resize', $arg);
        }

        return true;
    }

    
    
    }

?>
