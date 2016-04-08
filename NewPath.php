<?php

/**
 * Created by PhpStorm.
 * User: yago
 * Date: 26/03/16
 * Time: 10:46
 */
class NewPath
{
    const CROP_SIGNAL = "_cp";
    const SCALE_SIGNAL = "_sc";
    const WIDTH_SIGNAL = "_w";
    const HEIGHT_SIGNAL = "_h";
    
    private $configuration;
    private $fileSystem;

    function __construct($configuration)
    {
        $this->configuration = $configuration;
        $this->fileSystem = new FileSystem();
    }

    public function composeNewPath($imagePath) {

        if($this->existsOutputFileName()) {
            $newPath = $this->configuration->obtainOutputFileName();
        } else {
            $newPath = $this->buildNewPathName($imagePath);
        }

        return $newPath;
    }

    private function buildNewPathName($imagePath) {
        $newPath = $this->configuration->obtainCache()
            .$this->obtainFileName($imagePath)
            .$this->obtainWidthSignal()
            .$this->obtainHeightSignal()
            .$this->obtainCropSignal()
            .$this->obtainScaleSignal()
            .$this->obtainExtension($imagePath);
        return $newPath;
    }

    private function obtainFileName($imagePath) {
        return $this->fileSystem->file_get_md5($imagePath);
    }

    private function obtainExtension($imagePath) {
        return $this->fileSystem->file_get_extension($imagePath);
    }

    private function obtainCropSignal() {

        $crop = $this->configuration->obtainCrop();
        $result = "";

        if (isset($crop) && $crop == true)
            $result = self::CROP_SIGNAL;

        return $result;
    }

    private function obtainScaleSignal() {

        $scale = $this->configuration->obtainScale();
        $result = "";

        if (isset($scale) && $scale == true)
            $result = self::SCALE_SIGNAL;

        return $result;
    }

    private function obtainWidthSignal() {

        $width = $this->configuration->obtainWidth();
        $result = "";

        if (!empty($width))
            $result = self::WIDTH_SIGNAL.$width;

        return $result;
    }

    private function obtainHeightSignal() {

        $height = $this->configuration->obtainHeight();
        $result = "";

        if (!empty($height))
            $result = self::HEIGHT_SIGNAL.$height;

        return $result;
    }

    private function existsOutputFileName() {
        return $this->configuration->obtainOutputFileName();
    }

}