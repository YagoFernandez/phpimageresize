<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 21:24
 */
class DefaultCommand
{
    const THUMBNAIL_OPTION = " -thumbnail ";
    const QUALITY_OPTION = " -quality ";

    public function obtainCommand($imagePath, $newPath, $configuration) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();

        $command = $configuration->obtainConvertPath()
            ." " . escapeshellarg($imagePath)
            .SELF::THUMBNAIL_OPTION. (!empty($h) ? 'x':'')
            . $w ."". (isset($opts['maxOnly']) && $opts['maxOnly'] == true ? "\>" : "")
            .SELF::QUALITY_OPTION. escapeshellarg($opts['quality'])
            ." ". escapeshellarg($newPath);

        return $command;
    }
}