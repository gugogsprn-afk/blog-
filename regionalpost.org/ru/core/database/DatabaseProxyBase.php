<?php

    /**
     * Description of DatabaseProxyBase
     *
     * @author vahekha
     */
    abstract class DatabaseProxyBase {

        protected $NormalModeAfterTransactionEnd = true;
        protected $pf = '';
        protected $suff = '';
                
        protected $configName = '';

        public function __construct() {
            $_conf = array();
            
            require ROOTDIR . "_conf.php";
            $config = empty($this->configName)?$_conf:$_conf[$this->configName];
            
            $this->connect($config);
        }

        public function setSuffix($suffix) {
            $this->suff = $suffix;
        }

        // Query prepare //
        protected function FormatQuery($query) {
            return str_replace('#__', $this->pf, str_replace('_#',$this->suff,$query));
        }
        /*
        protected function BindParams($params) {
            if (!is_array($params) || count($params)==0) return $query;
            foreach ($params as $key => $value) {
                $query = str_replace(':'.$key, $this->EscapeValue($value), $query);
            }
            return $query;
        }
        */
        
                
        // destructor
        public function __destruct() {
            //$this->Dispose();
        }

        protected abstract function connect($config);

      
        
        public abstract function NormalizeExecMode();
        public abstract function transactionBegin($normalizeModeAfterTransactionEnd = true);
        public abstract function transactionCommit();
        public abstract function transactionRollback();

        public abstract function Query($query);
        public abstract function Fetch($query);
        public abstract function Scalar($query);
        public abstract function Fill($query, $keyColumn = '');

        public abstract function ReadRow();
        public abstract function ReadAll($key = '', $constantItems = null);

        public abstract function RowCount($isSelect = true);
        public abstract function LastID();

        public abstract function PaginateQuery($query, $startIndex, $rowCount);
        public abstract function EscapeValue($value, $quoteValue = true);

        public abstract function Freeup();
        public abstract function Dispose(); 
        
        public abstract function AffectedRows();
    }

?>
