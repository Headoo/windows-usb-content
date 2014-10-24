<?php
ini_set('memory_limit', '1024M');

require 'vendor/autoload.php';
require 'src/WatImage.php';
require 'src/Qaw.php';


$picturesFolder             = (string) (filter_input(INPUT_GET, 'mediaPath') === '') ? 'pictures' : filter_input(INPUT_GET, 'picturesFolder');
$doneFolder                 = (string) (filter_input(INPUT_GET, 'mediaPath') === '') ? 'saved' : filter_input(INPUT_GET, 'doneFolder') ;
$recoveryFolder             = (string) (filter_input(INPUT_GET, 'mediaPath') === '') ? 'saved' : filter_input(INPUT_GET, 'recoveryFolder') ;

$qaw                        = new src\Qaw();
$qaw->rightPath             = __DIR__ . '/../../../../';
$qaw->rightHardDrive        = 'C';
$qaw->qrCodeWidthRatio      = 33;
$qaw->getHardDrive();
$qaw->outputFileExtension   = '.jpg';
$qaw->setPicturesFolder($picturesFolder);
$qaw->setDoneFolder($doneFolder);
$qaw->createNewFolder($doneFolder . '/DEALT');
$qaw->setRecoveryFolder($recoveryFolder);
$qaw->listDirectoryPicturesAndApplyId();
$qaw->createQrCode($url = 'https://headoo.com/qr/', $padding = 10);
$qaw->applyQrCodeAsWatermark(100, 'bottom right');
$qaw->createIniFile();
$qaw->deleteQrCode();
$qaw->savePicturesToRecovery();
$qaw->deleteOriginalPictures();