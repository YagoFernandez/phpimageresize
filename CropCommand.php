<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 22:01
 */
class CropCommand
{
    const RESIZE_OPTION = " -resize ";
    const SIZE_OPTION = " -size ";
    const CANVAS_COLOR_OPTION = " xc:";
    const QUALITY_OPTION = " +swap -gravity center -composite -quality ";

    public function obtainCommand($imagePath, $newPath, $configuration) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();
        $resize = $this->composeResizeOptions($imagePath, $configuration);

        $cmd = $configuration->obtainConvertPath()
            ." ". escapeshellarg($imagePath)
            .self::RESIZE_OPTION. escapeshellarg($resize)
            .self::SIZE_OPTION. escapeshellarg($w ."x". $h)
            .self::CANVAS_COLOR_OPTION. escapeshellarg($opts['canvas-color'])
            .self::QUALITY_OPTION. escapeshellarg($opts['quality'])
            ." ".escapeshellarg($newPath);

        return $cmd;
    }



    public function isPanoramic($imagePath) {
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