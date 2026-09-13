<?php
/**
 * 
 *
 * @author Sight©
 */
class ItemFile extends TableEditableItem {
    
    const UPLOAD_MODE_FILE = 'single_file_mode';
    const UPLOAD_MODE_THUMB = 'single_thumb_mode';
    const UPLOAD_MODE_BOTH = 'single_both_mode';
    
    protected $table = '#__files';
    protected $idField = 'id';
    protected $FieldArray = Array('id', 'parent_id', 'type', 'filename','thumbname','isdefault','img_width', 'img_height', 'tmb_width', 'tmb_height','descr','descr_lg1','descr_lg2','tag');
    protected $CollectionFieldArray = Array('id', 'parent_id', 'type', 'filename','thumbname','isdefault','img_width', 'img_height', 'tmb_width', 'tmb_height','descr','descr_lg1','descr_lg2','tag');
    
    public function __construct($mId) {
        parent::__construct($mId);
    }
    
    protected function OnLoad($success) {
        if ($success) {
            $fileName = $this->getProp('filename');
            $thumbName = $this->getProp('thumbname');
            $this->setProp('filename', $fileName); // buckup old names for later update ready ( setProp is overriden in this class )
            $this->setProp('thumbname', $thumbName); // buckup old names for later update ready ( setProp is overriden in this class )
        }
    }
    protected function OnUpdate($success) {
        if ($success) {
            $fileName = $this->getProp('old_filename');
            $newName = $this->getProp('filename');
            if (!empty($fileName) && $fileName!=='.' && $fileName!=='..') {
                if (empty($newName) || $fileName !== $newName) {
                    $file = ROOTDIR . $fileName;
                    if (file_exists($file))
                        @unlink($file);
                }
                $this->setProp('old_filename', null);
            }

            $thumbName = $this->getProp('old_thumbname');
            $newThumb = $this->getProp('thumbname');
            if (!empty($thumbName) && $thumbName!=='.' && $thumbName!=='..') {
                if (empty($newThumb) || $thumbName !== $newThumb) {
                    $file = ROOTDIR . $thumbName;
                    if (file_exists($file))
                        @unlink($file);
                }
                $this->setProp('old_thumbname', null);
            }
        }
   }
    
    /**
     * 
     * @return ItemFile
     */
    public static function CreateEmpty($parentID,$type) {
        $file = new ItemFile(null);
        $file->setProp('parent_id', $parentID);
        $file->setProp('type', $type);
        $file->Insert();
        return $file;        
    }
    
    public static function loadFrom($parentID,$type) {
        $DB=  DatabaseProvider::provide();
        $row = $DB->Fetch('SELECT id, parent_id, type, filename, thumbname, isdefault, img_width, img_height, tmb_width, tmb_height, descr,descr_lg1,descr_lg2,tag FROM #__files WHERE parent_id='.intval($parentID).' AND type='.$DB->EscapeValue($type).' LIMIT 1');
        $file = null;
        if ($row!==false) {
            $file = new ItemFile(_VARINT($row['id']));
            $file->bindData($row);
        }
        return $file;
    }
    
    
    public static function FinFileID($parentID,$type) {
        $DB=  DatabaseProvider::provide();
        $id = $DB->Scalar('SELECT id FROM #__files WHERE parent_id='.intval($parentID).' AND type='.$DB->EscapeValue($type).' LIMIT 1');
        return _VARINT($id);
    }
    
    
   

    
    public function Removefile($thumb) {
        $fleName = $thumb==true?$this->getProp('thumbname'):$this->getProp('filename');
        $dbMember = $thumb==true?'thumbname':'filename';
        $mimeMember = $thumb==true?'mime_thumb':'mime_type';
        $thumbwMember = $thumb==true?'tmb_width':'img_width';
        $thumbhMember = $thumb==true?'tmb_height':'img_height';
        
        if (!empty($fleName)) {
            $file = ROOTDIR.$fleName;
            if (file_exists($file)) {
                $ok = @unlink($file);
                if (!$ok) throw new EPageError('Error deleting file');
            }
        }
        
        $DB = DatabaseProvider::provide();
        $DB->Query("UPDATE ".$this->table." SET ".$dbMember."=NULL,".$mimeMember."=NULL,".$thumbwMember."=0,".$thumbhMember."=0 WHERE id=".$this->ID);
    }

    
    public function getJsonCollection($pageSize,$startIndex,$pageNum,$orderby) {
        
        $ItemCollection = $this->Collection();
        $ItemCollection->ordering =empty($orderby)?'pos':$orderby; // TODO: ordering in grid
        
        $where = '';
        if (isset($this->Props) && is_array($this->Props)) {
            $qry = new QueryBuilder();
            $where =' WHERE '.$qry->BuildConditions($this->Props);
        }
        $DB = DatabaseProvider::provide();
        $poses = $DB->Fetch('SELECT max(pos) as maxpos,min(pos) as minpos FROM '.$this->table.$where);
        if ($poses==false) 
            throw new EPageError(EPageError::CUSTOM_ERROR,'failed find positions in collection');
        $ItemCollection->setConstants($poses);
        $ItemCollection->Load(false,0,0,false);
        $totalRows = $ItemCollection->totalCount(false);
        $arr = array("records" => $totalRows, "rows" => $ItemCollection->toArray());
        return SerilizeToJson($arr);
    }
    
   
    
    
    protected function BeforeDelete() {
        $fleName = $this->getProp('filename');
        $thumbName = $this->getProp('thumbname');
        if ($this->propExists('filename') && !empty($fleName)) {
            $file = ROOTDIR.$fleName;
            if (file_exists($file)) {
                $ok = @unlink($file);
                if (!$ok) throw new EPageError('Error deleting file');
            }
        }
        if ($this->propExists('thumbname') && !empty($thumbName)) {
            $file = ROOTDIR.$thumbName;
            if (file_exists($file)) {
                @unlink($file);
            }
        }
        return true;    
    }

    
    public function RemoveCollectionDB() {
        $coll = $this->Collection();
        $filter = $coll->getFilters();
        $DB = DatabaseProvider::provide();
        $DB->Query('DELETE FROM '.$this->table.' WHERE '.$filter);
    }
    
    
    public function makeDefault() {
        $coll = $this->Collection();
        $filter = $coll->getFilters();
        $DB = DatabaseProvider::provide();
        $DB->Query('UPDATE '.$this->table.' SET isdefault=0 WHERE '.$filter);
        $DB->Query('UPDATE '.$this->table.' SET isdefault=1 WHERE id='.$this->ID);
        return '"result" : "ok"';
    }
    
    
    public function handleImageUpload($PostedFile, $uploadFolder, $tmpFolder, $mode, $fullImageConfig, $thumbImageConfig = null,$targetExt='jpg',$targetName = '') {
        if (empty($uploadFolder) || empty($tmpFolder))
                throw new Exception('Upload folder not set');
        $parentID = $this->getProp('parent_id');
        $FileID = $this->ID;
        if (empty($FileID))
            throw new EPageError(EPageError::CUSTOM_ERROR,'file handle does not exists');
        if (empty($parentID))
            throw new EPageError(EPageError::CUSTOM_ERROR,'file parent is unknown');

        $uploadFolder = normalizeFolderPath($uploadFolder);
        $UploadDir = ROOTDIR . $uploadFolder;
        $tmpDir = ROOTDIR . $tmpFolder; // tmpfolder from base with trailing slash
        if (!is_dir($UploadDir)) {
            if (mkdir($UploadDir, 0777) === false) { 
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory create failed');
            }
        }

       
        $result = false;

        $tempFile = 'dummt';
        $fullFile = 'dummt';
        $thumbFile = 'dummt';

        $isFullImage = ($mode==ItemFile::UPLOAD_MODE_FILE || $mode==ItemFile::UPLOAD_MODE_BOTH);
        $isThumbImage = ($mode==ItemFile::UPLOAD_MODE_THUMB || $mode==ItemFile::UPLOAD_MODE_BOTH);
        
        try {
            if ($PostedFile['error'] != UPLOAD_ERR_OK) {
                switch ($PostedFile['error']) {
                    case UPLOAD_ERR_NO_FILE:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. No file sent.');
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. Exceeded filesize limit.');
                        break;
                    default:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. Unkown error');
                }
            }
            

            $uploadedFile = $PostedFile["tmp_name"];
            if (!is_uploaded_file($uploadedFile))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'file not uploaded');

            $fileManage = new FileManager();
            if (!$fileManage->isValidFile($uploadedFile,$PostedFile['name'],$PostedFile['size'],$PostedFile['type']))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Invalid file');

            if (empty($targetName)) {
            $fileParts = $fileManage->ParseAndCleanFileName($PostedFile['name']);
            if ($fileParts === false)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Invalid file name');
            } else {
                $pps = pathinfo($PostedFile['name']);
                $fileParts = array('name'=>$targetName,'ext'=>strtolower($pps['extension']));
            }
            
            $tempFile = $tmpDir.$fileParts['name'].'_'.$FileID.'.'.$fileParts['ext'];
            
            $tempFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $tempFile);
            $uploadedFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $uploadedFile);
            
            if (!move_uploaded_file($uploadedFile, $tempFile))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Error moving file');
            
            $imgManage = null;
            if ($targetExt==='*') {
                $targetExt = $fileParts['ext'];
            }
            if ($isFullImage===true) {
                $fullFile = $UploadDir.$fileParts['name'].'_'.$FileID.'.'.$targetExt;
                $fullFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $fullFile);
            
                if (file_exists($fullFile))
                    @unlink($fullFile);
                $imgManage = new ImageManager($tempFile);
                $isOk = $imgManage -> resizeImage(intval($fullImageConfig['w']),intval($fullImageConfig['h']), $fullImageConfig['mode']);
                if (!$isOk) 
                    throw new EPageError(EPageError::CUSTOM_ERROR,'unable to resize image');
                $isOk = $imgManage -> saveImage($fullFile, 100);
                if (!$isOk) 
                    throw new EPageError(EPageError::CUSTOM_ERROR,'unable to save image');
                $this->setProp('filename', $uploadFolder . $fileParts['name'].'_'.$FileID.'.'.$targetExt);
                $w = 0;
                $h = 0;
                list($w, $h) = getimagesize(ROOTDIR.$uploadFolder . $fileParts['name'].'_'.$FileID.'.'.$targetExt);
                $this->setProp('img_width', $w);
                $this->setProp('img_height', $h);
                $this->setProp('mime_type', $fileManage->mimeType);
                
            }
            if ($isThumbImage===true) {
                $thumbFile = $UploadDir.$fileParts['name'].'_'.$FileID.'_thumb.'.$targetExt;
                $thumbFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $thumbFile);
            
                if (file_exists($thumbFile))
                    @unlink($thumbFile);
                $imgManage = new ImageManager($tempFile);
                $isOk = $imgManage -> resizeImage(intval($thumbImageConfig['w']),intval($thumbImageConfig['h']), $thumbImageConfig['mode']);
                if (!$isOk) 
                    throw new EPageError(EPageError::CUSTOM_ERROR,'unable to resize thumb');
                $isOk = $imgManage -> saveImage($thumbFile, 100);
                if (!$isOk) 
                    throw new EPageError(EPageError::CUSTOM_ERROR,'unable to save thumb');
                $this->setProp('thumbname', $uploadFolder . $fileParts['name'].'_'.$FileID.'_thumb.'.$targetExt);
                $w = 0;
                $h = 0;
                list($w, $h) = getimagesize(ROOTDIR.$uploadFolder . $fileParts['name'].'_'.$FileID.'_thumb.'.$targetExt);
                $this->setProp('tmb_width', $w);
                $this->setProp('tmb_height', $h);
                $this->setProp('mime_thumb', $fileManage->mimeType);
            }
            $result = $this->Update();
            if (file_exists($tempFile))
                @unlink($tempFile);
        } catch (Exception $ex) {
            if (file_exists($tempFile))
                @unlink($tempFile);
            if (file_exists($fullFile))
                @unlink($fullFile);
            if (file_exists($thumbFile))
                @unlink($thumbFile);
            throw $ex;
        }
        return $result;
        
    }
    
    public function handleFileUpload($PostedFile, $uploadFolder,$dbMember) {
        if (empty($uploadFolder))
                throw new Exception('Upload folder not set');
        $parentID = $this->getProp('parent_id');
        $FileID = $this->ID;
        if (empty($FileID))
            throw new EPageError(EPageError::CUSTOM_ERROR,'file handle does not exists');
        if (empty($parentID))
            throw new EPageError(EPageError::CUSTOM_ERROR,'file parent is unknown');

        $uploadFolder = normalizeFolderPath($uploadFolder);
        $UploadDir = ROOTDIR . $uploadFolder;
        
        if (!is_dir($UploadDir)) {
            if (mkdir($UploadDir, 0777) === false) { 
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Directory create failed');
            }
        }

        $mimeTypeMember = $dbMember==='filename'?'mime_type':'mime_thumb';
       
        $fullFile = 'dummt';
        
        try {
            if ($PostedFile['error'] != UPLOAD_ERR_OK) {
                switch ($PostedFile['error']) {
                    case UPLOAD_ERR_NO_FILE:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. No file sent.');
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. Exceeded filesize limit.');
                        break;
                    default:
                        throw new EPageError(EPageError::CUSTOM_ERROR, 'Error uploading file. Unkown error');
                }
            }
            

            $uploadedFile = $PostedFile["tmp_name"];
            if (!is_uploaded_file($uploadedFile))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'file not uploaded');

            $fileManage = new FileManager();
            
            $fileParts = $fileManage->ParseAndCleanFileName($PostedFile['name']);
            if ($fileParts === false)
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Invalid file name');
            
            $fullFile = $UploadDir.$fileParts['name'].'_'.$FileID.'.'.$fileParts['ext'];
            
            $fullFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $fullFile);
            $uploadedFile = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $uploadedFile);
            
            if (file_exists($fullFile))
                 @unlink($fullFile);
            
            if (!move_uploaded_file($uploadedFile, $fullFile))
                throw new EPageError(EPageError::CUSTOM_ERROR, 'Error moving file');
            
            $this->setProp($dbMember, $uploadFolder . $fileParts['name'].'_'.$FileID.'.'.$fileParts['ext']);
            $this->setProp($mimeTypeMember, $PostedFile['type']);
            $result = $this->Update();
            
        } catch (Exception $ex) {
            if (file_exists($fullFile))
                @unlink($fullFile);
            throw $ex;
        }
        return $result;
    }


    public function setProp($name, $value) {
        if ($name==='filename') 
            parent::setProp('old_filename', $this->getProp('filename'));
        if ($name==='thumbname') 
            parent::setProp('old_thumbname', $this->getProp('thumbname'));
        parent::setProp($name, $value);
    }
    
    public static function setTag($id,$value) {
        $DB = DatabaseProvider::provide();
        $DB->Query('UPDATE #__files SET tag='.$DB->EscapeValue($value).' WHERE id='.$id);
    }
    
    public static function setDescrs($id,$values) {
        $DB = DatabaseProvider::provide();
        $DB->Query('UPDATE #__files SET 
                descr='.$DB->EscapeValue($values['descr']).',
                descr_lg1='.$DB->EscapeValue($values['descr_lg1']).',
                descr_lg2='.$DB->EscapeValue($values['descr_lg2']).'
                WHERE id='.$id);
    }
    
    public static function deleteItemCollection($parent_id,$folder,$prefix) {
        $DB = DatabaseProvider::provide();
        $DB->Query("DELETE FROM #__files WHERE parent_id=".intval($parent_id)." AND `type` LIKE '".$prefix."-%'");
        if (!is_dir(ROOTDIR . $folder . $parent_id . '/')) {
            rmdirr(ROOTDIR . $folder . $parent_id . '/');
        }
    }
}
    
?>