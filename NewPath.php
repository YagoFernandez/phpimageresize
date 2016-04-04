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
    private $fileSystem;

    function __construct($imagePath, $configuration)
    {
        $this->imagePath = $imagePath;
        $this->configuration = $configuration;
        $this->fileSystem = new FileSystem();
    }

    public function composeNewPath() {

        $opts = $this->configuration->asHash();

        $filename = $this->obtainFileName();
        $extension = $this->obtainExtension();
        $cropSignal = $this->obtainCropSignal();
        $scaleSignal = $this->obtainScaleSignal();
        $widthSignal = $this->obtainWidthSignal();
        $heightSignal = $this->obtainHeightSignal();


        $newPath = $this->configuration->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

        if($opts['output-filename']) {
            $newPath = $opts['output-filename'];
        }

        return $newPath;
    }

    private function obtainFileName() {
        return $this->fileSystem->file_get_md5($this->imagePath);
    }

    private function obtainExtension() {
        return $this->fileSystem->file_get_extension($this->imagePath);
    }

    private function obtainCropSignal() {

        $crop = $this->configuration->obtainCrop();
        $result = "";

        if (isset($crop) && $crop == true)
            $result = "_cp";

        return $result;
    }

    private function obtainScaleSignal() {

        $scale = $this->configuration->obtainScale();
        $result = "";

        if (isset($scale) && $scale == true)
            $result = "_sc";

        return $result;
    }

    private function obtainWidthSignal() {

        $width = $this->configuration->obtainWidth();
        $result = "";

        if (!empty($width))
            $result = "_w".$width;

        return $result;
    }

    private function obtainHeightSignal() {

        $height = $this->configuration->obtainHeight();
        $result = "";

        if (!empty($height))
            $result = "_h".$height;

        return $result;
    }

}