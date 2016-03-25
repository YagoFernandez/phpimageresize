<?php

include 'Configuration.php';
include 'ScaleCommand.php';

$mockGetimagesize = false;

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 21:54
 */
class ScaleCommandTest extends PHPUnit_Framework_TestCase
{

    public function testScaleCommand() {

        $opts = array(
            'crop' => false
        );

        $configuration = new Configuration($opts);
        $imagePath="https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $newPath="/newPath.jpg";

        $expectedCommand = "convert 'https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg' -resize '1' -quality '90' '/newPath.jpg'";

        $command = new ScaleCommand();

        $this->assertEquals($expectedCommand, $command->obtainCommand($imagePath, $newPath, $configuration));
    }
}