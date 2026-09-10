<?php
    
    class ConfigurationItem extends DataAdapter {

        // must override
        protected $table = '#__conf';
        protected $FieldArray = Array('id', 'group_id', 'name', 'value', 'params', 'title', 'description', 'type');
        
        public $fieldName = '';
        
        protected function OnLoad($success) {
            if ($success) {
                // image,text,select,textarea,number
                switch ($this->getProp('type')) {
                    case 'trans':
                        $vals = explode('|', $this->getProp('value'));
                        $this->setProp('valuetext',  implode('<br/>', $vals));
                        break;
                    case 'select':
                        $cases = array();
                        $val = explode('|', $this->getProp('params'));
                        foreach ($val as $value) {
                            list($key, $vle) = explode('-', $value);
                            $cases[] = array('value' => $key, 'text' => $vle);
                            if ($this->getProp('value') == $key)
                                $this->setProp('valuetext',$vle);
                        }
                        $this->setProp('cases', $cases);
                        break;
                    case 'image':
                        $cases = array();
                        $values = explode(';', $this->getProp('value'));
                        $val = explode('|', $this->getProp('params'));
                        $title = '';
                        foreach ($val as $value) {
                            list($key, $vle) = explode('-', $value);
                            $cases[] = array('value' => $key, 'text' => $vle);
                            if (trim($values[0]) == trim($key))
                                $title = $vle;
                        }
                        $this->setProp('cases', $cases);
                        $this->setProp('valuetext', $title . '&nbsp;&nbsp;&nbsp; ' . $values[1] . " px");
                        break;
                    case 'number':
                        $vle = $this->getProp('value');
                        if (!is_numeric($vle)) $vle=0;
                        $this->setProp('valuetext',$vle );
                        break;
                    case 'file':
                        $folder = $this->getProp('params');
                        $cases = array();
                        $files = scandir($folder);
                        $cases[] = array('value' => '', 'text' => '--not selected--');
                        $this->setProp('valuetext','--not selected--');
                        foreach ($files as $file) {
                            if (is_dir($folder.$file)) continue;
                            $cases[] = array('value' => $file, 'text' => $file);
                            if ($this->getProp('value') == $file)
                                $this->setProp('valuetext',$file);
                        }
                        $this->setProp('cases', $cases);
                        break;
                    default :
                        $vle = $this->getProp('value');
                        $this->setProp('valuetext',$vle );
                }
            }
            return $success;
        }


        public function ApplyChanges() {
            if ($this->getProp('name')=='meta_robots') {
                $this->updateRobots($this->getProp('value'));
            }
        }
        
        private function updateRobots($value) {
            $data = '';
            if ($value==='true') {
                $data = 'User-agent: *'.PHP_EOL.'Disallow: /cgi-bin/';
            } else {
                $data = 'User-agent: *'.PHP_EOL.'Disallow: /';
            }
            file_put_contents(ROOTDIR.'robots.txt',$data);
        }

        //
        

    }

?>
