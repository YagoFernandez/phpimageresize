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

        $w = $this->configuration->obtainWidth();
        $h = $this->configuration->obtainHeight();

        $filename = $fileSystem->file_get_md5($this->imagePath);
        $ext = $fileSystem->file_get_extension($this->imagePath);

        $cropSignal = isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "";
        $scaleSignal = isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "";
        $widthSignal = !empty($w) ? '_w'.$w : '';
        $heightSignal = !empty($h) ? '_h'.$h : '';

        $extension = '.'.$ext;

        $newPath = $this->configuration->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

        if($opts['output-filename']) {
            $newPath = $opts['output-filename'];
        }

        return $newPath;
    }
}