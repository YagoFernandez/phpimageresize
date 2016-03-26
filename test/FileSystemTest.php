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
}