<?php

    class CultureInfo {

        private $langList = array(
            'en'=>array(
                'id'=>'en',
                'short'=>'eng',
                'culture'=>'en-US',
                'suffix'=>'',
                'name'=>'English',
                'name_en'=>'English'
            ),
            'ru'=>array(
                'id'=>'ru',
                'short'=>'рус',
                'culture'=>'ru-RU',
                'suffix'=>'_lg1',
                'name'=>'Русский',
                'name_en'=>'Russian'
            )/*,
            'hy'=>array(
                'id'=>'hy',
                'short'=>'հայ',
                'culture'=>'hy-AM',
                'suffix'=>'_lg2',
                'name'=>'Հայարեն',
                'name_en'=>'Armenian'
            ),
            'fr'=>array(
                'id'=>'fr',
                'short'=>'fra',
                'culture'=>'fr-FR',
                'suffix'=>'',
                'name'=>'Ֆրանսերեն',
                'name_en'=>'French'
            )*/
        );
        
        static $currencyList = array(
            'usd'=>array(
                'id'=>'cur_usd',
                'short'=>'USD',
                'symbol'=>'$',
                'format'=>"$%s",
                'kendoformat'=>'$n',
                'decimals'=>2
            ),
            'eur'=>array(
                'id'=>'cur_eur',
                'short'=>'EUR',
                'symbol'=>'€',
                'format'=>'€%s',
                'kendoformat'=>'€n',
                'decimals'=>2
            ),
            'rur'=>array(
                'id'=>'cur_rur',
                'short'=>'RUR',
                'symbol'=>'р.',
                'format'=>'%s р.',
                'kendoformat'=>'n р.',
                'decimals'=>2
            ),
            'amd'=>array(
                'id'=>'cur_amd',
                'short'=>'AMD',
                'symbol'=>'դր.',
                'format'=>'%s դր.',
                'kendoformat'=>'n դր.',
                'decimals'=>0
            )
        );
        
        
        private $language = '';
        private $culture = '';
        private $suffix = '';
        private $name = '';
        
        
        private $currency = '';
        private $currency_rate = 1;
        private $currency_short = '';
        private $currency_format = '';
        private $currency_decimals = '';

        private $langArr = array();


        
        public function __construct($lang) {
            if (!array_key_exists($lang, $this->langList)) {
                $lang = 'hy';
            }
            $this->language = $lang;
            $this->culture = $this->langList[$lang]['culture'];
            $this->suffix = $this->langList[$lang]['suffix'];
            
            foreach ($this->langList as $value) {
                $this->langArr[] = $value;
            }
        }
        
        public function Culture() {
            return $this->culture;
        }
        
        public function Suffix() {
            return $this->suffix;
        }
        
        public function Language() {
            return $this->language;
        }
        
        public function LanguagePath() {
            return $this->language.'/';
        }
        
        
        public function LanguageList() {
            return $this->langList;
        }
        
        public function LanguageArr() {
            return $this->langArr;
        }
        
        public function convertLangName($ident) {
            if (empty($ident)) return 'hy';
            if ($ident==='_lg1') return 'ru';
            if ($ident==='_lg2') return 'en';
        }
        
        public function CurrencyList() {
            return self::$currencyList;
        }
        
        public function Currency() {
            return $this->currency;
        }
        public function CurrencyFormat() {
            return $this->currency_format;
        }
        
        public function CurrencyDecimals() {
            return $this->currency_decimals;
        }
         public function CurrencyShort() {
            return $this->currency_short;
        }
        
        
        public function Timezone() {
            if (!isset($_SESSION['client_tz']) || empty($_SESSION['client_tz'])) {
                return '+00:00';
            }
            return $_SESSION['client_tz'];
        }
        
        public function setCurrency($newCurrency) {
            if (!array_key_exists($newCurrency, self::$currencyList)) {
                $newCurrency = 'amd';
            }
            $this->currency = $newCurrency;
            $this->currency_rate = max(1,_VARDEC(Configuration::Load(self::$currencyList[$newCurrency]['id'])));
            $this->currency_short = self::$currencyList[$this->currency]['short'];
            $this->currency_format = self::$currencyList[$this->currency]['format'];
            $this->currency_decimals = self::$currencyList[$this->currency]['decimals'];
        }
        
        public static function isValidCurrency($currency) {
            return array_key_exists($currency, self::$currencyList);
        }
       
        public function CurrencyRate() {
            return $this->currency_rate;
        }
        
    
        public function Name() {
            return $this->name;
        }
        
        public function getCurrentCulture() {
            return $this->langList[$this->language];
        }

    }

?>
