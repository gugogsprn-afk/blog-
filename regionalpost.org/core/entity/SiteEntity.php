<?php

    /**
     * 
     *
     * @author Sight©
     */
    class SiteEntity extends DataAdapter {

        public function __construct($mId) {
            parent::__construct($mId);
        }

        protected function getSelectQuery() {
            $qry = new QueryBuilder();
            $this->ID = _VARINT($this->ID);
            $sID = DatabaseProvider::provide()->EscapeValue($this->ID);
            return $qry->Select($this->FieldArray, $this->table)->Where($this->idField . '=' . $sID . ' AND visible=1')->GetQuery();
        }

        protected function getInsertQuery() {
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        }

        protected function getUpdateQuery($excludeNulls) {
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        }

        protected function getDeleteQuery() {
            throw new EPageError(EPageError::INTERNAL_SERVER_ERROR);
        }

    }

?>