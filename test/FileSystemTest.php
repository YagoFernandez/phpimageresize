<?php

include 'FileSystem.php';

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 26/03/16
 * Time: 10:24
 */
class FileSystemTest extends PHPUnit_Framework_TestCase
{
    public function testFileGetExtension() {
        $filesystem = new FileSystem();
        $filename = "myfile.ext";
        $this->assertEquals('.ext', $filesystem->file_get_extension($filename));
    }

    public function testFileGetMd5() {
        $filesystem = new FileSystem();
        $filename = "https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $expected = "ffd81ed315aabd739b7f0e6c1d76c697";
        $this->assertEquals($expected, $filesystem->file_get_md5($filename));
    }
}
