<?php

use \src\Qaw as Qaw;
use \src\WatImage as WatImage;

    
class applyTest extends \Codeception\TestCase\Test
{    
    
   /**
    * @var \UnitTester
    */
    protected $tester;
    
    /**
     *
     * @var \src\Qaw
     */
    protected $qaw;
    
    /**
     *
     * @var \src\WatImage
     */
    protected $watImage;
    
    /**
     *
     * @var array
     */
    protected $picture;    
    
    /**
     *
     * @var string
     */
    protected $picturesFolder = 'testFolder';
    
    /**
     *
     * @var string
     */
    protected $doneFolder = 'testFolder/doneFolder';  
    
    /**
     *
     * @var string
     */
    protected $recoveryFolder = 'testFolder/recoveryFolder';  
    
    /**
     *
     * @var string
     */
    protected $qrCodeMessage = 'https://headoo.com/qr/';      
        
    /**
     *
     * @var string
     */
    protected $dir;    
    
   

    protected function _before()
    {
        $this->qaw      = new Qaw();
        $this->watImage = new WatImage();
        $this->dir      = __DIR__ . '/../../';
        $this->qaw->rightPath             = $this->dir;
        $this->qaw->rightHardDrive        = substr($this->dir, 0, 1);
        $this->qaw->qrCodeWidthRatio      = 33;
        $this->qaw->getHardDrive();
        $this->qaw->outputFileExtension   = '.jpg';
        $this->qaw->setPicturesFolder($this->picturesFolder);
        $this->qaw->setDoneFolder($this->doneFolder);
        $this->qaw->createNewFolder($this->doneFolder . '/DEALT');
        $this->qaw->setRecoveryFolder($this->recoveryFolder);
 
    }

    protected function _after()
    {       
    }

    /**
     * Test if there is a test picture in the specific folder
     */
    public function testIfPictureExist()
    {
        $picture        = $this->qaw->listDirectoryPicturesAndApplyId();
        
        $method         = $this->qaw->createQrCode($url = $this->qrCodeMessage, $padding = 10);
        $qrcode         = $this->dir . $this->picturesFolder . '/' .  $picture[0]['qrcode'];        
        
        $watermark      = $this->qaw->applyQrCodeAsWatermark(100, 'bottom right');
        $watermarkFile  = $this->dir . $this->doneFolder . '/' . $picture[0]['id'] . '/' . $picture[0]['qrcode']; 
        
        $this->qaw->createIniFile();
        
        $delete = $this->qaw->deleteQrCode();
        
        $saved = $this->qaw->savePicturesToRecovery();
        
        //$qaw->deleteOriginalPictures();
        
        //Assertions
        $this->assertTrue(is_dir($this->dir));
        
        $this->assertNotEmpty($picture);  
        
        $this->assertTrue($method);      
                    
        $this->assertTrue(file_exists($qrcode));
        
        $this->assertTrue($watermark);
        
        $this->assertTrue(file_exists($watermarkFile)); 
        
        $this->assertTrue($delete);
        
        $this->assertTrue($saved);           
    }     
}