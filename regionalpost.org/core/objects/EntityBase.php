<?php

    abstract class EntityBase  {


        protected $Props;

        public $IsCollectionItem = false;

        protected function __construct() {
            $this->Props = array();
        }

        public function setProp($name, $value) {
            $this->Props[$name] = $value;
        }

        public function getProp($name) {
            return isset($this->Props[$name]) ? $this->Props[$name] : null;
        }

        public function toArray() {
            return $this->Props;
        }

        public function propExists($name) {
            return array_key_exists($name, $this->Props);
        }

        public function removeProp($name) {
            unset($this->Props[$name]);
        }


    }

?>
