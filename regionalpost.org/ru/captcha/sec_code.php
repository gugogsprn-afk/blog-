<?php
session_start();
create_image();
exit();



function create_image()
{
    putenv('GDFONTPATH=' . realpath('.'));
    $length = 5;
    $FontBoxWidth = 15;
    $lineCount = 8;
    $width = 100;
    $height = 35; 
    $angleMin = -30;
    $angleMax = 30;
    $fontMin = 18;
    $fontMax = 20;
    $sessionKey = 'm_captch';
    if (intval($_REQUEST['g'])===1) {
        $sessionKey = 'g_captch';
    }
    unset($_SESSION[$sessionKey]);
    $_SESSION[$sessionKey] = '';
    $image = imagecreatetruecolor($width, $height);
    $borderWidth =0;
    $borderColor = imagecolorallocate($image, 0x00, 0x00, 0x00);
    $background = imagecolorallocate($image, 0xEB, 0xEB, 0xEB);
    if ($borderWidth>1) {
        imagefill($image, 0, 0, $borderColor);
        imagefilledrectangle($image, $borderWidth+1,$borderWidth+1, $width-(2*$borderWidth), $height-(2*$borderWidth), $background);    
    } else {
        imagefill($image, 0, 0, $background);
    }

    
    $linecolor = imagecolorallocate($image, 0x99, 0x99, 0x99);
    $ForeColor =  ImageColorAllocate($image, 0x44, 0x44, 0x44);
    $textcolor1 = imagecolorallocate($image, 0x77, 0x77, 0x77);
    
    $fonts = array('font1.ttf','font2.ttf','font3.ttf');

    $angle=0;
    for($i=0; $i < $lineCount; $i++) {
        imagesetthickness($image, rand(1,3));
        $angle = rand($angleMin,$angleMax);
        imageline($image, rand(0,$width), 0, rand(0,$width), $height, $linecolor);
        imagettftext($image, 16, $angle, mt_rand(0,$width), mt_rand(0,$height), $textcolor1, 'font3.ttf', ceil(mt_rand(0,9)));
    }

    $md5_hash = md5(rand(0,999)); 
    $captcha_code = substr($md5_hash, 15, $length); 
    $_SESSION[$sessionKey] = $captcha_code;

    $xs = round($width/2 - ($length*$FontBoxWidth)/2 );
    $y = round($height/2 + 7);
    $angle=0;
    for ($i=0; $i<$length; $i++) {
        $x = $xs + $i * $FontBoxWidth;
        $angle = rand($angleMin,$angleMax);
        $fontSize = ceil(rand($fontMin,$fontMax));
        if ($captcha_code[$i]=='a' || $captcha_code[$i]=='f') $fontSize = $fontSize + 5;
        imagettftext($image, $fontSize, $angle, $x, $y, $ForeColor, 'font5.ttf', $captcha_code[$i]); //$fonts[array_rand($cfonts)]
    }
    
    $last_modified = gmdate('D, d M Y H:i:s T', time()); 
    header("Last-Modified: $last_modified GMT"); 
    header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1 
    header("Cache-Control: post-check=0, pre-check=0", false); 
    header("Pragma: no-cache");     
    header("Content-Type: image/jpeg"); 

    //Output the newly created image in jpeg format 
    ImageJpeg($image);
   
    //Free up resources
    ImageDestroy($image);
}
?>