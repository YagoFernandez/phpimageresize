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
        $maxOnly = $opts['maxOnly'];
        $quality = $opts['quality'];

        $command = $configuration->obtainConvertPath()
            ." " . escapeshellarg($imagePath)
            .SELF::THUMBNAIL_OPTION. $this->obtainThumbnailOption($h)
            . $w ."". $this->obtainWidthOption($maxOnly)
            .SELF::QUALITY_OPTION. escapeshellarg($quality)
            ." ". escapeshellarg($newPath);

        return $command;
    }

    function obtainThumbnailOption($height) {

        $result = '';

        if (!empty($height))
            $result = 'x';

        return $result;
    }

    function obtainWidthOption($maxOnly) {

        $result = "";

        if (isset($maxOnly) && $maxOnly == true)
            $result = "\>";

        return $result;
    }
}