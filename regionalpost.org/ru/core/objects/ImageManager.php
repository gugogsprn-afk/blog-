<?php

    Class ImageManager {

        // *** Class variables ф
        private $image;
        public $width;
        public $height;
        private $imageResized;
        private $imageType;
        
        public $resempledWidth;
        public $resempledHeight;
        
        //private $transparency;
                
        
        function __construct($fileName) {
            // *** Open up the file
            $this->image = $this->openImage($fileName);

            // *** Get width and height
            $this->width = imagesx($this->image);
            $this->height = imagesy($this->image);
            
            /*
            if ($this->width>1100 || $this->height>1100) {
                throw new Exception('Файл слишком большой для оброботки');
            }
             * 
             */
        }

        ## --------------------------------------------------------

        private function openImage($file) {
            // *** Get extension
            $extension = strtolower(strrchr($file, '.'));
            $this->imageType = $extension;

            switch ($extension) {
                case '.jpg':
                case '.jpeg':
                    $img = imagecreatefromjpeg($file);
                    break;
                case '.gif':
                    $img = imagecreatefromgif($file);
                    break;
                case '.png':
                    $img = imagecreatefrompng($file);
                    break;
                default:
                    $img = false;
                    break;
            }
            return $img;
        }

        ## --------------------------------------------------------

        public function resizeImage($newWidth, $newHeight, $option = "auto",$toMaxSize = false) {
            $actualOption = $option;
            if ($actualOption==='smartyuncrop') {
                $actualOption = 'smarty';
            }
            // *** Get optimal width and height - based on $option
            $optionArray = $this->getDimensions($newWidth, $newHeight, $actualOption);
            $ImageIsSmaller = false;
            if ($toMaxSize) {
                if ($this->width<$newWidth && $this->height<$newHeight) {   
                    $ImageIsSmaller = true;
                    $optionArray = array('optimalWidth'=>$this->width,'optimalHeight'=>$this->height);
                }
            }

            $optimalWidth = $optionArray['optimalWidth'];
            $optimalHeight = $optionArray['optimalHeight'];

            $this->resempledWidth = $optimalWidth;
            $this->resempledHeight = $optimalHeight;

            // *** Resample - create image canvas of x, y size
            $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);

            if (!$this->imageResized)
                return false;

            // *** checking png file
            if ($this->imageType == '.png') {

                $transparencyIndex = imagecolortransparent($this->image);
                $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);
                if ($transparencyIndex >= 0) {
                    $transparencyColor = imagecolorsforindex($this->image, $transparencyIndex);
                }
                $transparencyIndex = imagecolorallocate($this->imageResized, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
                imagefill($this->imageResized, 0, 0, $transparencyIndex);
                imagecolortransparent($this->imageResized, $transparencyIndex);
                
                imagealphablending($this->imageResized, false);
                imagesavealpha($this->imageResized, true);
            }

            $ok = imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);
            if (!$ok)
                return false;

            
            // *** if option is 'smarty', then crop too
            if ($option == 'smarty' && $ImageIsSmaller==false) {
                $this->resempledWidth = $newWidth;
                $this->resempledHeight = $newHeight;
                $ok = $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
                if (!$ok)
                    return false;
            }
            return true;
        }

        ## --------------------------------------------------------

        private function getDimensions($newWidth, $newHeight, $option) {

            switch ($option) {
                case 'exact':
                    $optimalWidth = $newWidth;
                    $optimalHeight = $newHeight;
                    break;
                case 'portrait':
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight = $newHeight;
                    break;
                case 'landscape':
                    $optimalWidth = $newWidth;
                    $optimalHeight = $this->getSizeByFixedWidth($newWidth);
                    break;
                case 'auto':
                    $optionArray = $this->getSizeByAuto($newWidth, $newHeight);
                    $optimalWidth = $optionArray['optimalWidth'];
                    $optimalHeight = $optionArray['optimalHeight'];
                    break;
                case 'smarty':
                    $optionArray = $this->getOptimalCrop($newWidth, $newHeight);
                    $optimalWidth = $optionArray['optimalWidth'];
                    $optimalHeight = $optionArray['optimalHeight'];
                    break;
            }
            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }

        ## --------------------------------------------------------

        private function getSizeByFixedHeight($newHeight) {
            $ratio = $this->width / $this->height;
            $newWidth = $newHeight * $ratio;
            return $newWidth;
        }

        private function getSizeByFixedWidth($newWidth) {
            $ratio = $this->height / $this->width;
            $newHeight = $newWidth * $ratio;
            return $newHeight;
        }

        private function getSizeByAuto($newWidth, $newHeight) {
            if ($this->height < $this->width) {
            // *** Image to be resized is wider (landscape)
                $optimalWidth = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
            } elseif ($this->height > $this->width) {
            // *** Image to be resized is taller (portrait)
                $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
            } else {
            // *** Image to be resizerd is a square
                if ($newHeight < $newWidth) {
                    $optimalWidth = $newWidth;
                    $optimalHeight = $this->getSizeByFixedWidth($newWidth);
                } else if ($newHeight > $newWidth) {
                    $optimalWidth = $this->getSizeByFixedHeight($newHeight);
                    $optimalHeight = $newHeight;
                } else {
                    // *** Sqaure being resized to a square
                    $optimalWidth = $newWidth;
                    $optimalHeight = $newHeight;
                }
            }

            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }

        ## --------------------------------------------------------

        private function getOptimalCrop($newWidth, $newHeight) {

            $heightRatio = $this->height / $newHeight;
            $widthRatio = $this->width / $newWidth;

            if ($heightRatio < $widthRatio) {
                $optimalRatio = $heightRatio;
            } else {
                $optimalRatio = $widthRatio;
            }

            $optimalHeight = $this->height / $optimalRatio;
            $optimalWidth = $this->width / $optimalRatio;

            return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
        }

        
        public function cropExact($x,$y,$newWidth,$newHeight) {
            $crop = $this->image;
            $this->imageResized = @imagecreatetruecolor($newWidth, $newHeight);
            if (!$this->imageResized)
                return false;
            $ok = @imagecopyresampled($this->imageResized, $crop, 0, 0, $x, $y, $newWidth, $newHeight, $newWidth, $newHeight);
            if (!$ok)
                return false;
            return true;
        }
        
        ## --------------------------------------------------------

        private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight) {
            // *** Find center - this will be used for the crop
            $cropStartX = ( $optimalWidth / 2) - ( $newWidth / 2 );
            $cropStartY = ( $optimalHeight / 2) - ( $newHeight / 2 );

            $crop = $this->imageResized;
            //imagedestroy($this->imageResized);
            // *** Now crop from center to exact requested size
            $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
            if (!$this->imageResized)
                return false;
            
            if ($this->imageType == '.png') {

                $transparencyIndex = imagecolortransparent($crop);
                $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);
                if ($transparencyIndex >= 0) {
                    $transparencyColor = imagecolorsforindex($crop, $transparencyIndex);
                }
                $transparencyIndex = imagecolorallocate($this->imageResized, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
                imagefill($this->imageResized, 0, 0, $transparencyIndex);
                imagecolortransparent($this->imageResized, $transparencyIndex);
                
                imagealphablending($this->imageResized, false);
                imagesavealpha($this->imageResized, true);
            }
            
            
            $ok = imagecopyresampled($this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight);
            if (!$ok)
                return false;
            return true;
        }

        ## --------------------------------------------------------

        public function saveImage($savePath, $imageQuality = "100") {
            // *** Get extension
            $extension = strrchr($savePath, '.');
            $extension = strtolower($extension);
            $ok = true;
            switch ($extension) {
                case '.jpg':
                case '.jpeg':
                    if (imagetypes() & IMG_JPG) {
                        imageinterlace($this->imageResized, 1);
                        $ok = imagejpeg($this->imageResized, $savePath, $imageQuality);
                    }
                    break;

                case '.gif':
                    if (imagetypes() & IMG_GIF) {
                        imageinterlace($this->imageResized, 1);
                        $ok = imagegif($this->imageResized, $savePath);
                    }
                    break;

                case '.png':
                    // *** Scale quality from 0-100 to 0-9
                    $scaleQuality = round(($imageQuality / 100) * 9);

                    // *** Invert quality setting as 0 is best, not 9
                    $invertScaleQuality = 9 - $scaleQuality;

                    if (imagetypes() & IMG_PNG) {
                        // Non-interlaced PNG: Facebook/LinkedIn scrapers often fail on Adam7 interlaced PNGs (blank preview).
                        imageinterlace($this->imageResized, 0);
                        $ok = imagepng($this->imageResized, $savePath, $invertScaleQuality);
                    }
                    break;

                // ... etc

                default:
                    $ok = false;
                    // *** No extension - No save.
                    break;
            }
            imagedestroy($this->imageResized);
            return $ok;
        }

        public function getImage() {
            return $this->imageResized;
        }

        ## --------------------------------------------------------
    }

?>
