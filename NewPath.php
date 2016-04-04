<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 26/03/16
 * Time: 10:46
 */
class NewPath
{

    private $imagePath;
    private $configuration;

    function __construct($imagePath, $configuration)
    {
        $this->imagePath = $imagePath;
        $this->configuration = $configuration;
    }

    public function composeNewPath() {

        $fileSystem = new FileSystem();
        $opts = $this->configuration->asHash();

        $filename = $fileSystem->file_get_md5($this->imagePath);
        $extension = $fileSystem->file_get_extension($this->imagePath);

        $cropSignal = $this->obtainCropSignal($opts['crop']);
        $scaleSignal = $this->obtainScaleSignal($opts['scale']);
        $widthSignal = $this->obtainWidthSignal($this->configuration->obtainWidth());
        $heightSignal = $this->obtainHeightSignal($this->configuration->obtainHeight());


        $newPath = $this->configuration->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

        if($opts['output-filename']) {
            $newPath = $opts['output-filename'];
        }

        return $newPath;
    }

    private function obtainCropSignal($crop) {

        $result = "";

        if (isset($crop) && $crop == true)
            $result = "_cp";

        return $result;
    }

    private function obtainScaleSignal($scale) {

        $result = "";

        if (isset($scale) && $scale == true)
            $result = "_sc";

        return $result;
    }

    private function obtainWidthSignal($width) {

        $result = "";

        if (!empty($width))
            $result = "_w".$width;

        return $result;
    }

    private function obtainHeightSignal($height) {

        $result = "";

        if (!empty($height))
            $result = "_h".$height;

        return $result;
    }

}