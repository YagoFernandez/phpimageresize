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

        if($this->existsOutputFileName()) {
            $newPath = $this->configuration->obtainOutputFileName();
        } else {
            $newPath = $this->buildNewPathName();
        }
        
        return $newPath;
    }

    private function buildNewPathName() {
        $newPath = $this->configuration->obtainCache()
            .$this->obtainFileName()
            .$this->obtainWidthSignal()
            .$this->obtainHeightSignal()
            .$this->obtainCropSignal()
            .$this->obtainScaleSignal()
            .$this->obtainExtension();
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

    private function existsOutputFileName() {
        return $this->configuration->obtainOutputFileName();
    }

}