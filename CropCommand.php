<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 25/03/16
 * Time: 22:01
 */
class CropCommand
{
    public function obtainCommand($imagePath, $newPath, $configuration) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();
        $resize = $this->composeResizeOptions($imagePath, $configuration);

        $cmd = $configuration->obtainConvertPath() ." ". escapeshellarg($imagePath) ." -resize ". escapeshellarg($resize) .
            " -size ". escapeshellarg($w ."x". $h) .
            " xc:". escapeshellarg($opts['canvas-color']) .
            " +swap -gravity center -composite -quality ". escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);

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

        $hasCrop = (true === $opts['crop']);

        if(!$hasCrop && $this->isPanoramic($imagePath)):
            $resize = $w;
        endif;

        if($hasCrop && !$this->isPanoramic($imagePath)):
            $resize = $w;
        endif;

        return $resize;
    }
}