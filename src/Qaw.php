<?php
namespace src;

use \Endroid\QrCode\QrCode as qrCode;
use \src\WatImage as WatImage;

class Qaw {
    
    /**
     *
     * @var object
     */
    public $qrCode;
    
    /**
     *
     * @var array
     */
    protected $files = array();
    
    /**
     *
     * @var string
     */
    public $saveDirectory;
    
    /**
     *
     * @var string
     */
    public $rightHardDrive;
    
    /**
     *
     * @var string
     */
    public $rightPath; 
    
    /**
     *
     * @var string
     */
    public $picturesFolder; 
    
    /**
     *
     * @var string
     */
    public $doneFolder;
    
    /**
     *
     * @var string
     */
    public $recoveryFolder;    
    
    /**
     *
     * @var string
     */
    public $qrCodeTemporaryPath;
    
    /**
     *
     * @var string
     */
    public $outputFileExtension; 
    
    /**
     *
     * @var object
     */
    public $watImage;  
    
    
    /**
     *
     * @var string
     */
    public $qrCodeWidthRatio;
    
    /**
     * 
     */
    function __construct() 
    {
        $this->qrCode = new qrCode();
        $this->watImage = new WatImage();
    }
    
    /**
     * Choose the correct drive path to target
     */
    function getHardDrive()
    {    
        $drive = (string) substr(__DIR__, 0,1);

        if ($this->rightHardDrive !== $drive) {
            $this->rightPath = (string) $this->rightHardDrive . ':/' . $this->picturesFolder;
        }
    }
    
    /**
     * Set pictures folder
     * 
     * @param  string $folder Folder to use
     * @return string
     */
    function setPicturesFolder($folder)
    {
        return (string) $this->picturesFolder = (string) $this->rightPath . $folder;
    }

    /**
     * Set done folder
     * 
     * @param  string $folder Folder to use
     * @return string
     */
    function setDoneFolder($folder)
    {
        return (string) $this->doneFolder = (string) $this->rightPath . $folder;
    }
    
    /**
     * Set recovery folder
     * 
     * @param  string $folder Folder to use
     * @return string
     */
    function setRecoveryFolder($folder)
    {
        return (string) $this->recoveryFolder = (string) $this->rightPath . $folder;
    }    
    
    /**
     * Set QrCode temporary path
     * 
     * @return string
     */
    function setQrCodeTemporaryPath()
    {
        return (string) $this->qrCodeTemporaryPath = (string) $this->rightPath . '/qrCode.jpg';
    }    
    

    /**
     * Set QrCode temporary path
     * 
     * @return string
     */
    function createNewFolder($folder)
    {
        $fullPath = $this->rightPath . $folder;
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
    }    
    
    /**
     * Set qrcode size depending on image
     * 
     * @param  string $picture Picture path
     * @return integer
     */
    private function setQrCodeSize($picture) 
    {
        $size       = getimagesize($picture);
        $fileRatio  = ($size[0] > $size[1]) ? $size[0]/$size[1] : $size[1]/$size[0];
        return (integer) ceil((($size[0] * $this->qrCodeWidthRatio)/100)/$fileRatio);
    }
    
    /**
     * Apply watermark to picture and save it to another folder on the disk
     * 
     * @param  string $url Url to set inide the qrcode
     * @return boolean
     */
    public function execute($url) 
    {

        $this->listDirectory();

        if (!empty($this->files)) {
            foreach ($this->files as $key => $val) {
               $id = substr(md5(rand()),0,5); 

               $directoryToSaveTo      =   $this->doneFolder . '/' . $id;
               $savedPicture           =   $directoryToSaveTo . '/' . $id . $this->outputFileExtension;
               $iniFile                =   $directoryToSaveTo . '/' . $id . '.ini';    
               $currentImageFilename   =   $this->picturesFolder . '/' . $val;

               $qrCodeSize = $this->setQrCodeSize($currentImageFilename);
               
               $qrCodeText = $url . $id;
               
               $this->qrCode->setText($qrCodeText);
               $this->qrCode->setSize($qrCodeSize);
               $this->qrCode->setPadding(10);
               $this->qrCode->save($this->qrCodeTemporaryPath);

               mkdir($directoryToSaveTo, 0777, true);

               //Apply watermark
               $this->watImage->setImage(array('file' => $currentImageFilename, 'quality' => 100)); // file to use and export quality
               $this->watImage->setWatermark(array('file' => $this->qrCodeTemporaryPath, 'position' => 'bottom right')); // watermark to use and it's position
               $this->watImage->applyWatermark();
               if (!$this->watImage->generate($savedPicture)) {
                   print_r($this->watImage->errors);
               }

               //Save producer infos
               $datas = "uid = '$id'" . "\r\n";
               $datas .= "filename = '$id'" . "\r\n";
               $datas .= "extension = 'jpg'" . "\r\n";

               $handle = fopen($iniFile, 'w+');
               if (fwrite($handle, $datas)) {
                   fclose($handle);
               }

               //Delete qrcode file
               if (file_exists($this->qrCodeTemporaryPath)) {
                   @unlink($this->qrCodeTemporaryPath);
               }

               echo "$currentImageFilename // TREATED!<br/>" . "\r\n";
               sleep(1);
           }
        }
        
        return true;
       
    }
    
    /**
     * Save current pictures
     * 
     * @return boolean
     */
    public function savePicturesToRecovery() 
    {  
        foreach ($this->files as $key => $val) {
            
            if ($this->recoveryFolder !== "") {
                $newFilePath             =   $this->recoveryFolder . '/' . $val;            
                $newPictureName          =   str_replace($this->outputFileExtension, '_DONE_' . time() . $this->outputFileExtension , $newFilePath);
                $currentImageFilename    =   $this->picturesFolder . '/' . $val;

                if (!is_dir($this->recoveryFolder)) {
                    mkdir($this->recoveryFolder, 0777, true);
                }

                rename($currentImageFilename, $newPictureName);

                if (file_exists($currentImageFilename)) {
                    unlink($currentImageFilename);
                }

                echo "$currentImageFilename // ORIGINAL moved, saved and renamed!<br/>" . "\r\n";
            }
       }
       
       return true;
    }    
    
    
    /**
     * List all pictures in this directory
     * 
     * @param string $directory Directory that contains files
     * @return array
     */
    private function listDirectory() 
    {
        if (is_dir($this->picturesFolder)){
            $this->files = scandir($this->picturesFolder);
        } else {
            echo 'No image directory';
        }

        foreach ($this->files as $key => $var) {
            if (($var === '.') || ($var === '..') || (substr($var, -4, 1) !== '.')) {
                unset($this->files[$key]);
            }
        }

        return (array) $this->files;
    }
    
    /**
     * Secure pictures filenames
     * 
     * @param  string $string Filename to secure
     * @return string
     */
    private function secureFilenames($string)
    {
        // Transliterate non-ascii characters to ascii
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', trim(strtolower($string)));

        // Do other search and replace
        $searches = array(' ', '&', '/');
        $replaces = array('-', 'and', '-');
        $str1 = str_replace($searches, $replaces, $str);

        // Make sure we don't have more than one dash together because that's ugly
        $str2 = preg_replace("/(-{2,})/", "-", $str1 );

        // Remove all invalid characters
        $str3 = preg_replace("/[^A-Za-z0-9-]/", "", $str2 );

        // Done!
        return $str3;        
    }
    
    
}