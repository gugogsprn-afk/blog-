<?php

    /**
     * Description of Mysql connector using mysqli
     *
     * @author vahekha
     */
    class MysqliDatabaseProxy extends DatabaseProxyBase {

        protected $configName = 'mysql';

        private $transaction_in_progress;
        /**
         *
         * @var mysqli
         */
        private $_mysqli;

        /**
         *
         * @var mysqli_result 
         */
        private $Result;

        protected function connect($config) {
            $this->pf = $config['prefix'];
            $this->_mysqli = new mysqli($config['host'], $config['user'], $config['passwd'], $config['dbname']);
            if ($this->_mysqli->connect_errno) {
                throw new Exception('Failed to connect to MySQL: (' . $this->_mysqli->connect_errno . ' HOST '.$config['host'].') ' . $this->_mysqli->connect_error);
            }
            
            
            /*
            $timezone = date('O');
            $timezone = substr($timezone,0,3).':'.substr($timezone, 3);
            */
            $timezone = '+00:00';
            
            /*
             * date_default_timezone_set('	Etc/GMT+0')
             * SET time_zone = "+00:00";
             * SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP);
             * 
             */    
            
            // $DB = DatabaseProvider::provide();
            // SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP);
            // $now = $DB->Scalar("SELECT NOW()"); // 2016-10-04 13:09:41
            // $tz = HttpContext::current()->culture()->Timezone(); // +04:00 ( - on set from fe to db , + on get from db to fe )
             
            
            
            $this->_mysqli->query("set character_set_client='utf8mb4'");
            $this->_mysqli->query("set character_set_results='utf8mb4'");
            $this->_mysqli->query("set collation_connection='utf8mb4_unicode_ci'");
            $this->_mysqli->query("SET SQL_BIG_SELECTS=1");
            $this->_mysqli->query("SET TIME_ZONE='".$timezone."'");
            
            register_shutdown_function(array($this, "__shutdown_check"));
            /* 
            echo date('d.m.Y H:i:s');
            $now = $this->Scalar("SELECT NOW()");
            echo '<br>'.$now;
            */
        }

        // transaction methods //
        public function NormalizeExecMode() {
            $this->NormalModeAfterTransactionEnd = true;
            $this->_mysqli->autocommit(true);
        }

        public function transactionBegin($normalizeModeAfterTransactionEnd = true) {
            /*
            if ($this->transaction_in_progress)
                throw new Exception ('Transaction already started');
            */

            $this->NormalModeAfterTransactionEnd = $normalizeModeAfterTransactionEnd;
            $this->_mysqli->autocommit(false);
            // $this->_mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE); -- not working
            $this->transaction_in_progress = true;
        }

        public function transactionCommit() {
            $this->_mysqli->commit();
            $this->transaction_in_progress = false;
            if ($this->NormalModeAfterTransactionEnd)
                $this->NormalizeExecMode();
        }

        public function transactionRollback() {
            $this->_mysqli->rollback();
            $this->transaction_in_progress = false;
            if ($this->NormalModeAfterTransactionEnd)
                $this->NormalizeExecMode();
        }
        
        public function __shutdown_check() {
            if ($this->transaction_in_progress) {
                $this->transactionRollback();
            }
        }


        /*         * ******************** */
        public function Query($query) {
            $query = $this->FormatQuery($query);
            
            $this->Result = $this->_mysqli->query($query);
            if ($this->Result === false) {
                throw new EDataBaseError($this->_mysqli->errno, $this->_mysqli->error);
                return false;
            }
            return true;
        }

        public function Fetch($query) {
            $query = $this->FormatQuery($query);
            $this->Result = $this->_mysqli->query($query);
            if ($this->Result === false) {
                throw new EDataBaseError($this->_mysqli->errno, $this->_mysqli->error);
                return false;
            }
            if ($this->RowCount() <= 0) {
                return false;
            }
            $ret = $this->Result->fetch_assoc();
            return $ret;
        }

        public function Scalar($query) {
            $query = $this->FormatQuery($query);
            $this->Result = $this->_mysqli->query($query);
            if ($this->Result === false) {
                throw new EDataBaseError($this->_mysqli->errno, $this->_mysqli->error);
                return false;
            }
            $ret = $this->Result->fetch_row();
            if (is_null($ret) || count($ret) <= 0)
                return false;
            return $ret[0];
        }

        public function Fill($query, $keyColumn = '') {
            $query = $this->FormatQuery($query);
            $this->Result = $this->_mysqli->query($query);
            if ($this->Result === false) {
                throw new EDataBaseError($this->_mysqli->errno, $this->_mysqli->error);
                return false;
            }
            $ret = array();
            if ($this->RowCount() <= 0)
                return $ret;
           
            if (!empty($keyColumn)) {
                while ($row = $this->Result->fetch_assoc()) {
                    $ret[$row[$keyColumn]] = $row;
                }
            } else {
                while ($row = $this->Result->fetch_assoc()) {
                    $ret[] = $row;
                }
            }
            return $ret;
        }

        public function ReadRow() {
            return $this->Result->fetch_assoc();
        }

        public function ReadAll($key = '', $constantItems = null) {
            $ret = array();
            if ($this->RowCount() <= 0)
                return $ret;
            if (!empty($key)) {
                while ($row = $this->Result->fetch_assoc()) {
                    if (isset($constantItems) && is_array($constantItems)) {
                        $row = array_merge($row, $constantItems);
                    }
                    $ret[$row[$key]] = $row;
                }
            } else {
                while ($row = $this->Result->fetch_assoc()) {
                    if (isset($constantItems) && is_array($constantItems)) {
                        $row = array_merge($row, $constantItems);
                    }
                    $ret[] = $row;
                }
            }
            return $ret;
        }

        public function RowCount($isSelect = true) {
            return ($isSelect == true ? $this->Result->num_rows : $this->_mysqli->affected_rows);
        }

        public function LastID() {
            return $this->_mysqli->insert_id;
        }

        public function Freeup() {
            if (!($this->Result instanceof mysqli_result))
                return;
            $this->Result->free();
        }

        public function PaginateQuery($query, $startIndex, $rowCount) {
            $startIndex = intval($startIndex);
            $rowCount = intval($rowCount);
            if ($rowCount <= 0) {
                throw new Exception('wrong row count to fetch');
                return false;
            }
            return $query . " LIMIT $startIndex,$rowCount";
        }
        
        public function AffectedRows() {
            return $this->_mysqli->affected_rows;
        }

        public function EscapeValue($value, $quoteValue = true) {
            if ($value === null) {
                $value = '';
            } else {
                if ($quoteValue) {
                    $value = "'" . $this->_mysqli->real_escape_string($value) . "'";
                } else {
                    $value = $this->_mysqli->real_escape_string($value);
                }
            }
            if ($value==='' && $quoteValue)
                $value = "''";
            return $value;
        }
        
        

        public function __destruct() {
            //$this->Dispose();
        }

        public function Dispose() {
            $this->Freeup();
            $this->_mysqli->close();
        }

        

    }

?>
