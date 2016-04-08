<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 9/04/16
 * Time: 0:05
 */
class Command
{

    public function isPanoramic($imagePath) {
        list($width,$height) = getimagesize($imagePath);
        return $width > $height;
    }

    public function composeResizeOptions($imagePath, $configuration) {
        
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