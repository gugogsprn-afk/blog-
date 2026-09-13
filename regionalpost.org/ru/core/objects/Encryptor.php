<?php

    class Encryptor {

        public function __construct() {
        }
        
        public function newMD5() {
            return md5(generateString(10));
        }
        
        public function MD5($data) {
            return md5($data);
        }


        public function SaltMD5($word,$salt) {
            return md5(md5($word).$salt);
        }
        
        public function Encrypt($hash,$data) {
            $extraHash = $this->newMD5();
            $wl = strlen($data);
            $pos = mt_rand(2, 30-$wl);
            $pl = strlen($pos);
            $exhashp1 = substr($extraHash,$pl,$pos-$pl);
            $exhashp2 = substr($extraHash,$pos+$wl,32 - $pos - $wl - 2);
            $mixed = $exhashp1.$data.$exhashp2.$pl.$wl;
            $hashp1 = substr($hash,0,16);
            $hashp2 = substr($hash,16);
            return $pos.$hashp1.$mixed.$hashp2;
        }
        
        public function Decrypt($mixedHash) {
            if (!preg_match('/^[0-9a-f]{64}$/i',$mixedHash)) {
                return false;
            }
            $hashp2 = substr($mixedHash,48,16);
            $mixedwp1 = substr($mixedHash,0,48);
            $pl = intval(substr($mixedwp1,46,1));
            $wl = intval(substr($mixedwp1,47,1)); 
            $pos = substr($mixedwp1, 0, $pl)-$pl;
            $hashp1 = substr($mixedwp1,$pl,16);
            $mixedwp2 = substr($mixedwp1,$pl+16);
            $data = substr($mixedwp2,$pos,$wl);
            $hash = $hashp1.$hashp2;
            
            if (empty($hash) || empty($data) || !preg_match('/^[0-9a-f]{32}$/i',$hash)) {
                return false;
            }
            return array('data'=>$data,'hash'=>$hash);    
        }
    
        
    
    }

?>
