<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 22:01
 */
class ScaleCommand
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

    private function isPanoramic($imagePath) {
        list($width,$height) = getimagesize($imagePath);
        return $width > $height;
    }

    private function composeResizeOptions($imagePath, $configuration) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();

        $resize = "x".$h;

        $hasCrop = (true === $configuration->obtainCrop());

        if(!$hasCrop && $this->isPanoramic($imagePath)):
            $resize = $w;
        endif;

        if($hasCrop && !$this->isPanoramic($imagePath)):
            $resize = $w;
        endif;

        return $resize;
    }
}