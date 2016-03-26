<?php

include 'Configuration.php';
include 'DefaultCommand.php';

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 21:47
 */
class DefaultCommandTest extends PHPUnit_Framework_TestCase
{

    public function testDefaultCommand() {

        $configuration = new Configuration();
        $imagePath="/imagePath.jpg";
        $newPath="/newPath.jpg";

        $expectedCommand = "convert '/imagePath.jpg' -thumbnail x1 -quality '90' '/newPath.jpg'";

        $command = new DefaultCommand();

        $this->assertEquals($expectedCommand, $command->obtainCommand($imagePath, $newPath, $configuration));
    }
}