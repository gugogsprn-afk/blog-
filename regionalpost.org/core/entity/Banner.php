<?php
class Banner {
    
    public static function Collection($urlID,$place = '') {
        
        $DB = DatabaseProvider::provide();
        
        $urlID =intval($urlID);
        $context =intval(HttpContext::current()->IdentityThumbprint);
        if (!empty($place))
            $place = $DB->EscapeValue($place,false);
        
        $sql = "SELECT tm.id, tm.name, tm.content, tm.validdate, tm.context, tm.place, tm.priority
                    FROM #__banners tm WHERE
                     tm.id IN  (
                            SELECT tbu.item_id
                            FROM #__urlcache tu
                            INNER JOIN #__urlmapper tbu ON (tbu.itemtype='banner' AND (tbu.url_id = tu.id OR (tbu.children=1 AND tu.urltop LIKE CONCAT('%,',tbu.url_id,',%'))))
                            INNER JOIN #__banners tb ON tb.id = tbu.item_id
                            WHERE tb.validdate>=NOW() 
                            AND (tb.context & ".$context.")=".$context."
                            ".(empty($place)?"":"AND tb.place='".$place."'")."    
                            AND tu.id=".$urlID.")
                            ORDER BY tm.place, tm.priority DESC";
        
        $DB->Query($sql);
        $items = array();
        
        if ($DB->RowCount()<=0) return $items;
        
        while ($row = $DB->ReadRow()) {
           $items[$row['place']][] = $row;
        }
        return $items;
    }


}
?>