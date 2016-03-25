<?php

include 'Configuration.php';

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 21:47
 */
class DefaultCommandTest extends PHPUnit_Framework_TestCase
{

    function defaultShellCommand($configuration, $imagePath, $newPath) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();

        $command = $configuration->obtainConvertPath() ." " . escapeshellarg($imagePath) .
            " -thumbnail ". (!empty($h) ? 'x':'') . $w ."".
            (isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "") .
            " -quality ". escapeshellarg($opts['quality']) ." ". escapeshellarg($newPath);

        return $command;
    }

    public function testDefaultCommand() {
        $configuration = new Configuration();
        $imagePath="/imagePath.jpg";
        $newPath="/newPath.jpg";

        $expectedCommand = "convert '/imagePath.jpg' -thumbnail x1 -quality '90' '/newPath.jpg'";

        $this->assertEquals($expectedCommand, $this->defaultShellCommand($configuration, $imagePath, $newPath));
    }
}