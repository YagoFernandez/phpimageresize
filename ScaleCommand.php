<?php

require 'Command.php';
/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 22:01
 */
class ScaleCommand extends Command
{
    const RESIZE_OPTION = " -resize ";
    const QUALITY_OPTION = " -quality ";

    public function obtainCommand($imagePath, $newPath, $configuration) {
        $opts = $configuration->asHash();
        $resize = $this->composeResizeOptions($imagePath, $configuration);

        $cmd = $configuration->obtainConvertPath()
            ." ". escapeshellarg($imagePath)
            .self::RESIZE_OPTION. escapeshellarg($resize)
            .self::QUALITY_OPTION. escapeshellarg($opts['quality'])
            . " " . escapeshellarg($newPath);

        return $cmd;
    }

}