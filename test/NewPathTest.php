<?php

include 'NewPath.php';
include 'Configuration.php';
include 'FileSystem.php';

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 26/03/16
 * Time: 10:50
 */
class NewPathTest extends PHPUnit_Framework_TestCase
{
    public function testComposeNewPathWithoutOutputFileName() {

        $configuration = new Configuration();
        $filename = "https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $newPath = new NewPath($filename, $configuration);

        $expected = "./cache/ffd81ed315aabd739b7f0e6c1d76c697_w1_h1_sc..jpg";
        $this->assertEquals($expected, $newPath->composeNewPath());
    }

    public function testComposeNewPathWithOutputFileName() {

        $opts = array(
            'output-filename' => "myfilename.ext"
        );

        $configuration = new Configuration($opts);
        $filename = "https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $newPath = new NewPath($filename, $configuration);

        $this->assertEquals("myfilename.ext", $newPath->composeNewPath());
    }
}