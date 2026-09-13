<?php

    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of fileManager
     *
     * @author Vahe
     */
    class FileManager {

        public $AllowExt = array('jpg', 'png', 'gif', 'jpeg');
        public $MimeTypes = array('image/pjpeg'=>'jpg', 'image/jpeg'=>'jpg', 'image/png'=>'png', 'image/gif'=>'gif');
        public $minFileSize = 100; // bytes
        public $maxFileSize = 3000000; // 5 mb
       
        const NONE='none';
        const NOT_IMAGE = 'FILENOTIMAGE';    
        const SIZE_ERROR = 'FILESIZEERROR';
      

        public $fileExtension;
        public $mimeType;
        
        public function __construct() {
            
        }
        
        
        public function translateError($errNum) {
            switch ($errNum) //$_FILES['uploadfile']['error']
            {
            case 1: return 'Размер файла превышает допустимое значение UPLOAD_MAX_FILE_SIZE'; break;
            case 2: return 'Размер файла превышает допустимое значение MAX_FILE_SIZE'; break;
            case 3: return 'Не удалось загрузить часть файла'; break;
            case 4: return 'Файл не был загружен'; break;
            case 6: return 'Отсутствует временная папка.'; break;
            case 7: return 'Не удалось записать файл на диск.'; break;
            case 8: return 'PHP-расширение остановило загрузку файла.'; break;
            }
            return 'Неизвестная ошибка';
        }

        public function isValidFile($tempPath,$fileName,$upsize,$fileType) {
            // mime check from header
            if (!array_key_exists($fileType, $this->MimeTypes))
                return self::NOT_IMAGE;
            
            // size and mime check from file
            $imageinfo = getimagesize($tempPath);
            if (empty($imageinfo[0]) || empty($imageinfo[1]))
                return self::NOT_IMAGE;
            if (!array_key_exists($imageinfo['mime'], $this->MimeTypes))
                return self::NOT_IMAGE;
            
            $this->fileExtension = $this->MimeTypes[$imageinfo['mime']];
            $this->mimeType = $imageinfo['mime'];
                    
            // extension check
            $fileParts = pathinfo($fileName);
            
            if (!in_array($fileParts['extension'], $this->AllowExt))
                return self::NOT_IMAGE;
            
            // size check
            if ($upsize<$this->minFileSize || $upsize>$this->maxFileSize)
                return self::SIZE_ERROR;
            
            $upsize = filesize($tempPath);
            // size check of file
            if ($upsize<$this->minFileSize || $upsize>$this->maxFileSize)
                return self::SIZE_ERROR;
            
            // TODO: add random string at the end of file
            // TODO: limit file count for item
            
            return self::NONE;
        }
        
        function ParseAndCleanFileName($filename,$maxLenght=50) {
            $arr = array();
            $fileParts = pathinfo($filename);
            $ext = strtolower($fileParts['extension']);
            $baseName = $fileParts['filename'];
            
            $st = mb_strtolower($baseName);
            $st = str_replace(
          array(
                '?', '!', '.', ',', ':', ';', '*', '(', ')', '{', '}', '[', ']', '%', '#', '№', '@', '$', '^', '-', '+', '/', '\\', '=', '|', '"', '\'', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ы', 'э', ' ', 'ж', 'ц', 'ч', 'ш', 'щ', 'ь', 'ю', 'я'), 
          array(
                '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'j', 'i', 'e', '-', 'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya'), $st);
            $st = preg_replace("/[^a-z0-9-]/", "", $st);
            $st = trim($st, '-');
            $name = preg_replace("/-{2,}/", "-", $st);

            $name = str_replace('-','_',$name);
            if (strlen($name)>$maxLenght) 
                $name=  substr ($name, 0,$maxLenght);
            if (empty($name) || empty($ext)) return false;
            return array('name'=>$name,'ext'=>$ext);
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
          /*
          private $errorList = array( UPLOAD_ERR_INI_SIZE=>'The uploaded file exceeds max file size',
          UPLOAD_ERR_FORM_SIZE=>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
          UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
          UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
          UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
          UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
          UPLOAD_ERR_EXTENSION=>'A PHP extension stopped the file upload.');
       
           *  public function setDirectory($folder,$autoCreateDir = false) {
            if (!is_dir($folder) && $autoCreateDir) {
                if (mkdir($temp_dir, 4664) === false) { // only root upload folder
                    throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory create failed');
                }
            } else {
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory does not exists');
            }
            $this->UploadFolder = $folder;
        }
        
   
         */
        
        /*
          public function Upload($tempPath,$originalName,$error,$type,$targetName,$targetExt) {
            //
            if ($error != UPLOAD_ERR_OK) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error uploading file');
            
            if (!is_uploaded_file($tempPath)) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error uploading file');
            
            if (!$this->isValidFile($tempPath,$type))
                throw new EPageError(EPageError::CUSTOM_ERROR,'Invalid file');
            
            $fileParts = $this->ParseFileName($targetName);
            if ($fileParts===false) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Invalid file name');
            
            $targetPath = $this->UploadFolder.$fileParts['name'].'.'.$fileParts['ext'];
            
            if (file_exists($targetPath)) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'file already exists');
            
            if (!move_uploaded_file($tempPath, $targetPath)) 
                throw new EPageError(EPageError::CUSTOM_ERROR,'Error writing file');
            
            return $fileParts['name'].'.'.$fileParts['ext'];
        }
        
         */
//        public function Upload($InputName, $targetPrefix = '', $allowSameName = false) {
//            $result = array();
//            $files = $_FILES[$InputName];
//            if (!isset($files['name']))
//                throw new EPageError(EPageError::CUSTOM_ERROR, 'file not selected');
//
//            foreach ($files["name"] as $i => $name) {
//                $error = $files['error'][$i];
//                
//                $oneRes = array();
//                if ($error == UPLOAD_ERR_OK) {
//
//                    $targetPath = $this->UploadFolder . basename($files["name"][$i]);
//                    $uploadedFile = $files["tmp_name"][$i];
//
//                    if (is_uploaded_file($uploadedFile)) {
//                        if ($this->isValidFile($files["tmp_name"][$i])) {
//                            if (file_exists($targetPath)) {
//                                if ($allowSameName === false) {
//                                    $oneRes['error'] = "file already exists";
//                                    $oneRes['id'] = $i;
//                                } else {
//                                    
//                                }
//                            }
//                            if (move_uploaded_file($uploadedFile, $targetPath)) {
//                                $oneRes['status'] = "ok";
//                                $oneRes['id'] = $i;
//                            } else {
//                                $oneRes['error'] = "Error moving uploaded file";
//                                $oneRes['id'] = $i;
//                            }
//                        } else {
//                            $oneRes['error'] = "Invalid file";
//                            $oneRes['id'] = $i;
//                        }
//                    } else {
//                        $oneRes['error'] = "Error uploading file";
//                        $oneRes['id'] = $i;
//                    }
//                } else {
//                    $oneRes['error'] = "Error uploading file";
//                    $oneRes['id'] = $i;
//                }
//                $result[] = $oneRes;
//            }
//
//            return $result;
//        }

    }

?>