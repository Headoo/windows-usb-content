<?php

use \src\Qaw as Qaw;

class applyTest extends \Codeception\TestCase\Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;
    
    protected $qaw;  

    protected function _before()
    {
        $this->qaw = new Qaw();
        echo var_dump(__DIR__ . '/../../');
        /*$this->qaw->rightPath             = __DIR__ . '/../../../../';
        $this->qaw->rightHardDrive        = 'C';
        $this->qaw->qrCodeWidthRatio      = 33;
        $this->qaw->getHardDrive();
        $this->qaw->outputFileExtension   = '.jpg';
        $this->qaw->setPicturesFolder($picturesFolder);
        $this->qaw->setDoneFolder($doneFolder);
        $this->qaw->createNewFolder($doneFolder . '/DEALT');
        $this->qaw->setRecoveryFolder($recoveryFolder);
        $this->qaw->setQrCodeTemporaryPath();*/
    }

    protected function _after()
    {       
    }

    /**
     * We test if main directory specified has been created
     */
    public function testIfPictureFolderExists()
    {
        $folder = __DIR__ . '/../../testFolder';
        return $this->assertTrue(is_dir($folder));
    }    
}