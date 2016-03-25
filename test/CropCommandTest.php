<?php

include 'Configuration.php';
include 'CropCommand.php';

$mockGetimagesize = false;

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 21:54
 */
class CropCommandTest extends PHPUnit_Framework_TestCase
{

    public function testCropCommand() {

        $opts = array(
            'crop' => true
        );

        $configuration = new Configuration($opts);
        $imagePath="https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $newPath="/newPath.jpg";

        $expectedCommand = "convert 'https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg' -resize 'x1' -size '1x1' xc:'transparent' +swap -gravity center -composite -quality '90' '/newPath.jpg'";

        $command = new CropCommand();

        $this->assertEquals($expectedCommand, $command->obtainCommand($imagePath, $newPath, $configuration));
    }
}