<?php
    
    class Slide {
        
        public static function Collection() {
        
        $DB = DatabaseProvider::provide();
                
        $DB->Query("SELECT tm.id, tm.name_# as name, tm.descr_# as descr, tm.url, tf.filename
                    FROM #__slides tm
                    INNER JOIN #__files tf on tf.parent_id=tm.id AND tf.type='slides'
                    WHERE tm.visible=1
                    ORDER BY tm.pos");
        $items = array();
        if ($DB->RowCount()<=0) return $items;
        
        while ($row = $DB->ReadRow()) {
           $row['name'] =  mb_str_replace('&', '&amp;', $row['name']); 
           if (!empty($row['descr']) && (substr($row['descr'], 0, 13)==='https://youtu' || substr($row['descr'], 0, 17)==='https://www.youtu')) {
               $row['window'] = $row['descr'];
               unset($row['descr']);
           }
           $items[] = $row;
        }
        
        return $items;
    }
        
        
    }
  
?>