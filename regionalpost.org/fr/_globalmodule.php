<?php

    function echo_line($mixed) {
        echo '<br />'.$mixed;    
    }
    
    function echo_hr() {
        echo '<hr>';    
    }
    
    function pre_dump($mixed) {
        echo '<pre>';
        var_dump($mixed);    
        echo '</pre>';
    }
    
    function pre_arr($mixed) {
        echo '<pre>';
        print_r($mixed);    
        echo '</pre>';
    }
    
    function alert( $txt ) {
        echo "<script language=JavaScript>alert('".$txt."');</script>";
    }
    
    function _REQUEST($key) {
        $value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
        if (is_string($value)) {
            $value = mb_str_replace("\0", '', $value);
            $value = mb_str_replace("%00", '', $value);
            $value = trim($value);
        }
        return $value;
    }
    function _REQINT($key,$default=0) {
        $value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
        if (is_numeric($value)) {
            $value = intval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    function _REQDEC($key,$default=0) {
        $value = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
        if (is_numeric($value)) {
            $value = floatval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    function _POST($key,$default=null) {
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        if (is_string($value)) {
            $value = mb_str_replace("\0", '', $value);
            $value = mb_str_replace("%00", '', $value);
            $value = trim($value);
        }
        return $value;
    }
    function _POSTINT($key,$default=0) {
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        if (is_numeric($value)) {
            $value = intval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    function _POSTDEC($key,$default=0) {
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        if (is_numeric($value)) {
            $value = floatval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    
    function isLocalHost() {
        $whitelist = array(
                '127.0.0.1',
                '::1'
        );
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }
    
    function isHash($hash) {
         $hashRegex = "/^[A-Za-z0-9]{64}$/";
         return preg_match($hashRegex,$hash);
    }
    
    
    function _VAR($value,$default=null) {
        $value = isset($value) ? $value : $default;
        if (is_string($value)) {
            $value = mb_str_replace("\0", '', $value);
            $value = mb_str_replace("%00", '', $value);
            $value = trim($value);
        }
        return $value;
    }
    function _VARINT($value,$default=0) {
        $value = isset($value) ? $value : $default;
        if (is_numeric($value)) {
            $value = intval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    function _VARDEC($value,$default=0) {
        $value = isset($value) ? $value : $default;
        if (is_numeric($value)) {
            $value = floatval($value);
        } else {
            $value = $default;
        }
        return $value;
    }
    
    function generateString($length = 6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
    }
    
    
    
    function _APPNAME() {
        $value = isset(HttpContext::current()->request()->AppName) ? HttpContext::current()->request()->AppName : '';
        return $value;
    }
    
   
    
    function SerilizeToJson($mixed) {
        return json_encode($mixed, JSON_UNESCAPED_UNICODE);
    }
    
    function DeserilizeJson($jsonData) {
        $v = json_decode($jsonData,true);
        /*
        var_dump($jsonData);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                echo ' - Ошибок нет';
            break;
            case JSON_ERROR_DEPTH:
                echo ' - Достигнута максимальная глубина стека';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                echo ' - Некорректные разряды или не совпадение режимов';
            break;
            case JSON_ERROR_CTRL_CHAR:
                echo ' - Некорректный управляющий символ';
            break;
            case JSON_ERROR_SYNTAX:
                echo ' - Синтаксическая ошибка, не корректный JSON';
            break;
            case JSON_ERROR_UTF8:
                echo ' - Некорректные символы UTF-8, возможно неверная кодировка';
            break;
            default:
                echo ' - Неизвестная ошибка';
            break;
        }
        */
        
        return $v;
    }
    
    /**
     * Simple function to demonstrate how to control file access using "accessControl" callback.
     * This method will disable accessing files/folders starting from  '.' (dot)
     *
     * @param  string  $attr  attribute name (read|write|locked|hidden)
     * @param  string  $path  file path relative to volume root directory started with directory separator
     * @return bool|null
     * */
    function access($attr, $path, $data, $volume) {
        return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
                ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
                : null;                                    // else elFinder decide it itself
    }
    
    
    function translitWord($word) {
        $st = mb_strtolower($word);
        $st = str_replace(array(
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'', 
			'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я',
			'ա','բ','գ','դ','ե','զ','է','ը','թ','ժ','ի','լ','խ','ծ','կ','հ','ձ','ղ','ճ','մ','յ','ն','շ','ո','չ','պ','ջ','ռ','ս','վ','տ','ր','ց','ւ','փ','ք','և','օ','ֆ'
			), array(
            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', 
			'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya',
			'a','b','g','d','e','z','e','y','t','j','i','l','x','c','k','h','dz','x','ch','m','y','n','sh','o','ch','p','j','r','s','v','t','r','c','u','p','q','ev','o','f'), $st);
        $st = preg_replace("/[^a-z0-9-]/", "", $st);
        $st = trim($st, '-');
        /*
          $prev_st = '';
          do {
          $prev_st = $st;
          $st = preg_replace("/-[a-z0-9]-/", "-", $st);
          } while ($st != $prev_st);
         * 
         */
        $st = preg_replace("/-{2,}/", "-", $st);
        return $st;
    }
    
    
    function CleanUrl($url) {
        $st = mb_strtolower($url);
        $st = str_replace(array(
            '_','?', '!', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '\\', '=', '|', '"', '\'', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'), array(
            '-','-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',  '-', '-', '-', '-', '-',  '-', '-', '-', '-', '-', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya'), $st);
        $st = preg_replace("/([^a-z0-9-\.\/])/", "", $st);
        $st = trim($st, '-');
        
        $st = preg_replace("/-{2,}/", "-", $st);
        return $st;
    }
    
    
    function CleanFileName($filename,$maxLenght=50) {
        $st = mb_strtolower($filename);
        $st = str_replace(
      array(
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'', 
                            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я',
                            'ա','բ','գ','դ','ե','զ','է','ը','թ','ժ','ի','լ','խ','ծ','կ','հ','ձ','ղ','ճ','մ','յ','ն','շ','ո','չ','պ','ջ','ռ','ս','վ','տ','ր','ց','ւ','փ','ք','և','օ','ֆ'), 
      array(
            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', 
                            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya',
                            'a','b','g','d','e','z','e','y','t','j','i','l','x','c','k','h','dz','x','ch','m','y','n','sh','o','ch','p','j','r','s','v','t','r','c','u','p','q','ev','o','f'
                            ), $st);
        $st = preg_replace("/[^a-z0-9-_]/", "", $st);
        $st = trim($st, '-');
        $name = preg_replace("/-{2,}/", "-", $st);
        if (strlen($name)>$maxLenght) 
            $name=  substr ($name, 0,$maxLenght);
        
       
        if (empty($name)) 
            return false;
        return $name;
    }
    
    function ParseAndCleanFileName($filename,$maxLenght=50) {
        $fileParts = pathinfo($filename);
        
        $fileInfo['ext'] = strtolower($fileParts['extension']);
        $fileInfo['directory'] = $fileParts['dirname'];
        $fileInfo['nameext'] = $fileParts['basename'];
        $fileInfo['name'] = $fileParts['filename'];

        $st = mb_strtolower($fileInfo['name']);
        $st = str_replace(
      array(
            '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'', 
                            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я',
                            'ա','բ','գ','դ','ե','զ','է','ը','թ','ժ','ի','լ','խ','ծ','կ','հ','ձ','ղ','ճ','մ','յ','ն','շ','ո','չ','պ','ջ','ռ','ս','վ','տ','ր','ց','ւ','փ','ք','և','օ','ֆ'), 
      array(
            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', 
                            'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya',
                            'a','b','g','d','e','z','e','y','t','j','i','l','x','c','k','h','dz','x','ch','m','y','n','sh','o','ch','p','j','r','s','v','t','r','c','u','p','q','ev','o','f'
                            ), $st);
        $st = preg_replace("/[^a-z0-9-_]/", "", $st);
        $st = trim($st, '-');
        $name = preg_replace("/-{2,}/", "-", $st);
        if (strlen($name)>$maxLenght) 
            $name=  substr ($name, 0,$maxLenght);
        
        $fileInfo['nameclean'] = $name;
        
        if (empty($fileInfo['name']) || empty($fileInfo['ext'])) 
            return false;
        return $fileInfo;
    }
    
    
    function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }

        if (is_file($dirname)) {
            @chmod($dirname, 0664);
            return unlink($dirname);
        }

        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            rmdirr($dirname.'/'.$entry);
        }

        // Clean up
        $dir->close();
        return rmdir($dirname);
    }
    
    function ParseDate($dateStr,$separator = '.') {
        $test_arr  = explode($separator, $dateStr);
        try {
            if (@checkdate($test_arr[1], $test_arr[0], $test_arr[2])==FALSE)
                return null;
            return mktime(0, 0, 0, $test_arr[1], $test_arr[0], $test_arr[2]);
        } catch (Exception $ex) {
            return null;
        }
        return null;
    }
    
    function ParseDateTime($date,$format = 'd.m.Y H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) == $date))
            return null;
        return $d->getTimestamp();
    }
    
    function isValidDateTime($date, $format = 'd.m.Y H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function convertToOffset($timezone = 'UTC'){
        $time = new DateTime('now', new DateTimeZone($timezone));
        return $time->format('P');
    }
    
    function setRange($value,$min,$max) {
        if ($value<$min) return $min;
        if ($value>$max) return $max;
        return $value;
    }
    
    function setCondRange($value,$minstr,$maxstr) {
        if ($minstr=='' && $maxstr=='') return $value;
        if ($minstr=='' && $maxstr!='') {
            // - max
            if ($value>floatval($maxstr)) return floatval ($maxstr);
            return $value;
        }
        if ($minstr!='' && $maxstr=='') {
            // min +
            if ($value<floatval($minstr)) return floatval($minstr);
            return $value;
        }
        if ($minstr!='' && $maxstr!='') {
            // - max
            if ($value<floatval($minstr)) return floatval($minstr);
            if ($value>floatval($maxstr)) return floatval ($maxstr);
            return $value;
        }
        return $value;
    }
    
    function mb_str_replace($needle, $replacement, $haystack) {
        $needle_len = mb_strlen($needle);
        $replacement_len = mb_strlen($replacement);
        $pos = mb_strpos($haystack, $needle);
        while ($pos !== false)
        {
            $haystack = mb_substr($haystack, 0, $pos) . $replacement
                    . mb_substr($haystack, $pos + $needle_len);
            $pos = mb_strpos($haystack, $needle, $pos + $replacement_len);
        }
        return $haystack;
    }
    
    function mb_trim( $string ) { 
        $string = preg_replace( "/(^\s+)|(\s+$)/us", "", $string ); 
        return $string; 
   }

    function filterInput($text,$escapeLikes = true) {
        
        $text = trim($text);
        $word = mb_str_replace(';',' ',$text);
        $word = mb_str_replace('{','',$word);
        $word = mb_str_replace('}','',$word);
        $word = mb_str_replace('"',' ',$word);
        $word = mb_str_replace("'",' ',$word);
        if ($escapeLikes) {
            $word = mb_str_replace("%",'',$word);
            $word = mb_str_replace("_",'',$word);
        }
        $word = htmlspecialchars($word);
        // $word = preg_replace( "/(^\s+)|(\s+$)/us", "", $word ); 
        return $word;
    }
    
    function filter_string_polyfill($string) {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    function clearInput($input,$minLength=0,$maxLength=1000) {
        $word = trim(filter_string_polyfill($input));
        if (mb_strlen($word)<intval($minLength)) {
            return false;
        }

        if (mb_strlen($word)>$maxLength) {
            $word = mb_substr($word, 0, $maxLength);
        }
        return $word;
    }

    function clearInputAdv($input,$minLength=0,$maxLength=1000) {
        $word = trim(filter_string_polyfill($input));
        if (mb_strlen($word)<intval($minLength)) {
            return false;
        }

        if (mb_strlen($word)>$maxLength) {
            $word = mb_substr($word, 0, $maxLength);
        }
        return $word;
    }
    
    function int_Array($array) {
        $intArr = array();
        if (array_isset($array)) {
            foreach ($array as $Sid) {
                if (_VARINT($Sid)>0) {
                    $intArr[] = _VARINT($Sid);
                }
            }
        }
        return $intArr;
    }
    
    function int_Array_KeepIndexes($array) {
        $intArr = array();
        if (array_isset($array)) {
            $cnt = count($array);
            for ($idx = 0;$idx<$cnt;$idx++) {
                $intArr[$idx] = _VARINT($array[$idx]);
            }
        }
        return $intArr;
    }
    
    function isValidEmail($mail) {
        return preg_match('/^[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,3})$/',$mail);
    }
    function isValidPhone($phone) {
        return preg_match('/^[0-9- ]*$/',$phone);
    }
    function clearPhone($input) {
        return mb_ereg_replace("[^0-9]", "", $input);
    }
    
    
    function getAlphabet($lang) {
        $letters = array();
        switch ($lang) {
            case 'ru':
                $letters = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я');
                break;
        }
        return $letters;
    }
    
    function Monthname($num,$lang) {
        $arr = array();
        if ($lang=='hy') 
            $arr = array('հունվար','փետրվար','մարտ','ապրիլ','մայիս','հունիս','հուլիս','օգոստոս','սեպտեմբեր','հոկտեմբեր','նոյեմբեր','դեկտեմբեր');
        if ($lang=='ru') 
            $arr = array('январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');        
        if ($lang=='en') 
            $arr = array('january','febrary','march','april','may','june','july','august','september','october','november','december');        
        return $arr[$num-1];
    }
    function Monthnames($lang) {
        $arr = array();
        if ($lang=='hy') 
            $arr = array('հունվար','փետրվար','մարտ','ապրիլ','մայիս','հունիս','հուլիս','օգոստոս','սեպտեմբեր','հոկտեմբեր','նոյեմբեր','դեկտեմբեր');
        if ($lang=='ru') 
            $arr = array('январь','февраль','март','апрель','май','июнь','июль','август','сентябрь','октябрь','ноябрь','декабрь');        
        if ($lang=='en') 
            $arr = array('january','febrary','march','april','may','june','july','august','september','october','november','december');        
        return $arr;
    }
    
    function Weekname($num,$lang) {
        $arr = array();
        if ($lang=='hy') 
            $arr = array('Երկուշաբթի','Երեքշաբթի','Չորեքշաբթի','Հինգշաբթի','Ուրբաթ','Շաբաթ','Կիրակի');
        if ($lang=='ru') 
            $arr = array('Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');        
        if ($lang=='en') 
            $arr = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');        
        return $arr[$num-1];
    }
    
    function Weeknameshort($num,$lang) {
        $arr = array();
        if ($lang=='hy') 
            $arr = array('Երկ','Երք','Չրք','Հնգ','Ուր','Շբթ','Կիր');
        if ($lang=='ru') 
            $arr = array('Пн','Вт','Ср','Чт','Пт','Сб','Вс');        
        if ($lang=='en') 
            $arr = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');        
        return $arr[$num-1];
    }
    
    function Monthshort($num,$lang) {
        $arr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'); 
        if ($lang=='hy') 
            $arr = array('հնվ','փտվ','մրտ','ապր','մյս','հնս','հլս','օգս','սեպ','հոկ','նոյ','դեկ');
        if ($lang=='ru') 
            $arr = array('янв','фев','мар','апр','май','июн','июл','авг','сен','окт','ноя','дек');        
        if ($lang=='en') 
            $arr = array('jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec');        
        return $arr[$num-1];
    }
    
    function formatTime($t) {
        $s = round($t);
        $m = floor($s / 60);
        $h = floor($m/60);
        $s = floor($s % 60);
        $m = floor($m % 60);
        return $h.":".($m < 10 ? "0" : "").$m.":".($s < 10 ? "0" : "").$s;
    }
    
    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow)); 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 
    
    function formatBytesUnits($bytes) {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

    
    function array_isset($mixed) {
        return ($mixed!==null && is_array($mixed) && count($mixed)>0);
    }
    
    function stripText($text,$MaxLength,$lastChar='') {
        if (mb_strlen($text) > $MaxLength) {
            if ($lastChar!=='') {
                $ps = mb_strrpos(mb_substr($text, 0, $MaxLength), ',');
                if (!$ps)
                    $ps = $MaxLength;
                $text = mb_substr($text, 0, $ps);
            } else {
                $text = mb_substr($text, 0, $MaxLength);
            }
        }
        return $text;
    }
    
    
    function splitList($str) {
        return preg_split('/,/', $str, -1, PREG_SPLIT_NO_EMPTY);
        
        // array_filter( explode(",", $str ), 'strlen' );
    }
    
    
    
    function createTree(Array $data, $parent = 0){
        $tree = array();
        foreach ($data as $d) {
            if ($d['parent_id'] == $parent) {
                $children = createTree($data, $d['id']);
                // set a trivial key
                if (!empty($children)) {
                    $d['items'] = $children;
                }
                $tree[] = $d;
            }
        }
        return $tree;
    }

    
    function translateUploadError($errNum) {
        switch ($errNum) //$_FILES['uploadfile']['error']
        {
        case 1: return 'ERR_FILESIZELIMIT (E1)'; break;
        case 2: return 'ERR_FILESIZELIMIT (E2)'; break;
        case 3: return 'ERR_FILEUPLOADCHUNK'; break;
        case 4: return 'ERR_UPLOAD'; break;
        case 6: return 'ERR_TEMPFILE'; break;
        case 7: return 'ERR_SAVEFILE'; break;
        case 8: return 'ERR_UPLOADFILE'; break;
        }
        return 'ERR_UNKNOWN';
    }
    
    
    function parseDimensions($mixed) {
        $ret = array();
        list($ret['width'],$ret['height']) = explode('x', $mixed);
        $ret['width'] = _VARINT($ret['width']);
        $ret['height'] = _VARINT($ret['height']);
        return $ret;
    }
    
    
    function normalizeFolderPath($path) {
         if (substr($path, strlen($path)-1,1)!=='/') 
            $path = $path.'/';     
         return $path;
    }
    
    function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    
    function mb_ucfirst($string) {
        $strlen = mb_strlen($string);
        $firstChar = mb_substr($string, 0, 1);
        $then = mb_substr($string, 1, $strlen - 1);
        return mb_strtoupper($firstChar) . $then;
    }
    
    function redirectTo($reletiveUrl) {
        if (HttpContext::current()->request()->IsAjax()) {
            echo SerilizeToJson(array(
                    'reload'=>array(
                        array('key'=>'a','value'=>time())
                    ),
                    'url'=>  HttpContext::current()->culture()->LanguagePath().$reletiveUrl
                        ));
            exit();
        } 
        
        $reletiveUrl = $reletiveUrl.(strpos($reletiveUrl, '?')===false?'?a='.time():'&a='.time());
        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
        header("Pragma: no-cache"); // HTTP 1.0.
        header("Expires: 0"); // Proxies.
        header('Location: '.HttpContext::current()->request()->baseAddress.HttpContext::current()->culture()->LanguagePath().$reletiveUrl);
        exit();
    }
    
    
    function buildXml($assocArray,$rootKey='root') {
        $xmlContents = '<?xml version="1.0" encoding="UTF-8"?><'.$rootKey.'></'.$rootKey.'>';
        $itemXml = simplexml_load_string($xmlContents);
        foreach ($assocArray as $key=>$value) {
            $itemXml->addChild($key, $value);
        }
        return $itemXml->asXML();
    }
    
    function extractXml($xmlString) {
        if (empty($xmlString)) return array();
        $itemXml = simplexml_load_string($xmlString);
        $ret = array();
        foreach ($itemXml->children() as $element) {
            $ret[$element->getName()] = (string)$element;
        }
        return $ret;
    }
    
    
    function formatStack($trace) {
        if (!array_isset($trace)) 
            return '';
        $trace = array_reverse($trace);
        array_shift($trace);
        
    }
    
    
    $_LoremFull = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.';
    $_LoremTitles = array('Lorem ipsum','dolor sit amet','consectetuer','adipiscing','nonummy','nibh euismod','tincidunt ut laoreet','dolore magna','aliquam erat volutpat','nostrud exerci','suscipit lobortis','aliquip ex ea commodo','Duis autem vel','facilisis','molestie consequat','hendrerit in vulputate','claritatem insitam','quinta decima','fiant sollemnes','facilisis at vero','eros et accumsan','iusto odio dignissim','qui blandit','praesent luptatum','zzril','delenit augue','duis dolore','feugait nulla facilisi','Nam liber tempor cum','soluta nobis','eleifend option','congue nihil','imperdiet doming');
    $_LoremTags = array('Lorem','ipsum','dolor','sit','amet','consectetuer','adipiscing','elit','sed','diam','nonummy','nibh','euismod','tincidunt','ut','laoreet','dolore','magna','aliquam','erat','volutpat','Ut','wisi','enim','ad','minim','veniam','quis','nostrud','exerci','tation','ullamcorper','suscipit','lobortis','nisl','ut','aliquip','ex','ea','commodo','consequat','Duis','autem','vel','eum','iriure','dolor','in','hendrerit','in','vulputate','velit','esse','molestie','consequat','vel','illum','dolore','eu','feugiat','nulla','facilisis','at','vero','eros','et','accumsan','et','iusto','odio','dignissim','qui','blandit','praesent','luptatum','zzril','delenit','augue','duis','dolore','te','feugait','nulla','facilisi','Nam','liber','tempor','cum','soluta','nobis','eleifend','option','congue','nihil','imperdiet','doming','id','quod','mazim','placerat','facer','possim','assum','Typi','non','habent','claritatem','insitam','est','usus');
    $_LoremNames = array('Branden Gore','Carlo Hinkley','Aleta Mackley','Nathanael Kuss','Melodi Deak','Keira Juergens','Sylvia Kircher','Zora Kesner','Candida Mormon','Zelda Mercuri','Ok Billips','Julianne Rolland','Jung Bohnert','Cleta Tabron','Ricarda Walston','Mara Tiner','Lenita Spurlin','Weston Jenney','Parthenia Tunnell','Darryl Einhorn','Emilee Currie','Kenda Merkel','Katharina Raybon','Gregory Major','Willard Rowser','Esperanza Arbour','Sharon Cronkhite','Asa Hutsell','Ramona Hawthorne','Una Maier','Garfield Bogert','Pamala Vinton','Queen Valentino','Eustolia Berkowitz','Phebe Deford','Dianne Koury','Victor Schlottmann','Martine Jeffords','Margret Orlando','Kraig Hershey','Marline Hockman','Colleen Cumbie','Branda Heinlein','Louise Sauve','Carlton Kulig','Kortney Turbeville','Jeanine Gaiter','Enda Gourley','Garret Asuncion','Franchesca Pallas');
    $_LoremManufacts = array('Tatev','Lori','Arcax','New Zeland','Italy','Sisian','Megri','Spain','Candida','Madagaskar','Philipines','Rolland','Bohnert','Tabron','Walston','Tiner');
    
    
    $_LL = strlen($_LoremFull)-1;
    $_LT = count($_LoremTitles)-1;
    $_LG = count($_LoremTags)-1;
    $_LN = count($_LoremNames)-1;
    $_LM = count($_LoremManufacts)-1;
    
    function getLorem() {
        global $_LoremFull;
        return $_LoremFull;
    }
    
    function getRandomDescr($minLength) {
        global $_LoremFull,$_LL;
        $start = rand(0,floor($_LL/2));
        $length = rand($minLength,floor($_LL/2));
        return substr($_LoremFull, $start, $length);
    }
    
    function getRandomTitle() {
        global $_LoremTitles,$_LT;
        $idx = rand(0,$_LT);
        return ucfirst($_LoremTitles[$idx]);
    }
    
    function getRandomManufact() {
        global $_LoremManufacts,$_LM;
        $idx = rand(0,$_LM);
        return ucfirst($_LoremManufacts[$idx]);
    }

    function getRandomTags($minCount,$maxCount) {
        global $_LoremTags,$_LG;
        $cnt = rand($minCount,min($_LG,$maxCount));
        $arr = array();
        for ($i=0;$i<$cnt;$i++) {
            $idx = rand(0,$_LG);
            $arr[] = $_LoremTags[$idx];
        }
        return $arr;
    }
    function getRandomWord() {
        global $_LoremTags,$_LG;
        $idx = rand(0,$_LG);
        return ucfirst($_LoremTags[$idx]);
    }
    
    
    function getRandomName() {
        global $_LoremNames,$_LN;
        $idx = rand(0,$_LN);
        return ucfirst($_LoremNames[$idx]);
    }
    
    function getRandomNames($minCount,$maxCount) {
        global $_LoremNames,$_LN;
        $cnt = rand($minCount,min($_LN,$maxCount));
        $arr = array();
        for ($i=0;$i<$cnt;$i++) {
            $idx = rand(0,$_LN);
            $arr[] = $_LoremNames[$idx];
        }
        return $arr;
    }
    
    function getRandomDate($from,$to) {
        $fromD = ParseDate($from);
        $toD = ParseDate($to);
        $int= mt_rand($fromD,$toD);
        return date("d.m.Y",$int);
    }
    function getRandomTimeStamp($from,$to) {
        $fromD = ParseDate($from);
        $toD = ParseDate($to);
        $int= mt_rand($fromD,$toD);
        return $int;
    }
    
    function getRandomBit($maxBit,$minCount,$maxCount) {
        $props = array(1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144);
        $bits = array_slice($props,0,array_search($maxBit,$props)+1);
        shuffle($bits);
        $cnt = rand($minCount,$maxCount);
        if ($cnt===0) {
            return 0;
        }
        $val = 0;
        for ($i=0;$i<$cnt;$i++) {
            $val = $val | $bits[$i];
        }
        return $val;
    }
    
    /*
     * 
     * // json_encode() IMPLEMENTATION IF JSON EXTENSION IS MISSING
if (!function_exists("json_encode")) {

    function kcfinder_json_string_encode($string) {
        return '"' .
            str_replace('/', "\\/",
            str_replace("\t", "\\t",
            str_replace("\r", "\\r",
            str_replace("\n", "\\n",
            str_replace('"', "\\\"",
            str_replace("\\", "\\\\",
        $string)))))) . '"';
    }

    function json_encode($data) {

        if (is_array($data)) {
            $ret = array();

            // OBJECT
            if (array_keys($data) !== range(0, count($data) - 1)) {
                foreach ($data as $key => $val)
                    $ret[] = kcfinder_json_string_encode($key) . ':' . json_encode($val);
                return "{" . implode(",", $ret) . "}";

            // ARRAY
            } else {
                foreach ($data as $val)
                    $ret[] = json_encode($val);
                return "[" . implode(",", $ret) . "]";
            }

        // BOOLEAN OR NULL
        } elseif (is_bool($data) || ($data === null))
            return ($data === null)
                ? "null"
                : ($data ? "true" : "false");

        // FLOAT
        elseif (is_float($data))
            return rtrim(rtrim(number_format($data, 14, ".", ""), "0"), ".");

        // INTEGER
        elseif (is_int($data))
            return $data;

        // STRING
        return kcfinder_json_string_encode($data);
    }
}

     */

            
            
?>