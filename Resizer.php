<?php

require 'FileSystem.php';
require 'NewPath.php';
require 'DefaultCommand.php';
require 'CropCommand.php';
require 'ScaleCommand.php';

class Resizer {

    private $path;
    private $configuration;
    private $fileSystem;
    private $newPath;

    public function __construct($path, $configuration=null) {
        if ($configuration == null) $configuration = new Configuration();
        $this->checkPath($path);
        $this->checkConfiguration($configuration);
        $this->path = $path;
        $this->configuration = $configuration;
        $this->fileSystem = new FileSystem();
        $this->newPath = new newPath($configuration);
    }

    public function injectFileSystem(FileSystem $fileSystem) {
        $this->fileSystem = $fileSystem;
    }

    public function obtainFilePath() {
        $imagePath = '';

        if($this->path->isHttpProtocol()):
            $filename = $this->path->obtainFileName();
            $local_filepath = $this->configuration->obtainRemote() .$filename;
            $inCache = $this->isInCache($local_filepath);

            if(!$inCache):
                $this->download($local_filepath);
            endif;
            $imagePath = $local_filepath;
        endif;

        if(!$this->fileSystem->file_exists($imagePath)):
            $imagePath = $_SERVER['DOCUMENT_ROOT'].$imagePath;
            if(!$this->fileSystem->file_exists($imagePath)):
                throw new RuntimeException();
            endif;
        endif;

        return $imagePath;
    }
    
    public function obtainNewPath($imagePath) {
        $result = $this->newPath->composeNewPath($imagePath);
        return $result;
    }


    private function download($filePath) {
        $img = $this->fileSystem->file_get_contents($this->path->sanitizedPath());
        $this->fileSystem->file_put_contents($filePath,$img);
    }

    private function isInCache($filePath) {
        $fileExists = $this->fileSystem->file_exists($filePath);
        $fileValid = $this->fileNotExpired($filePath);

        return $fileExists && $fileValid;
    }

    private function fileNotExpired($filePath) {
        $cacheMinutes = $this->configuration->obtainCacheMinutes();
        $this->fileSystem->filemtime($filePath) < strtotime('+'. $cacheMinutes. ' minutes');
    }

    private function checkPath($path) {
        if (!($path instanceof ImagePath)) throw new InvalidArgumentException();
    }

    private function checkConfiguration($configuration) {
        if (!($configuration instanceof Configuration)) throw new InvalidArgumentException();
    }

    function doResize() {

        try {
            $imagePath = obtainFilePath();
        } catch (Exception $e) {
            return 'image not found';
        }

        $newPath = obtainNewPath($imagePath);

        $create = !isNewPathInCache($newPath, $imagePath);

        if($create == true):
            try {
                $cmd = selectCommand($imagePath, $newPath, $this->configuration);
                executeCommand($cmd);
            } catch (Exception $e) {
                return 'cannot resize the image';
            }
        endif;

        return $newPath;
    }

    function selectCommand($imagePath, $newPath, $configuration) {
        $opts = $configuration->asHash();
        $w = $configuration->obtainWidth();
        $h = $configuration->obtainHeight();

        $command = new DefaultCommand();

        if(!empty($w) and !empty($h)):
            $command = new CropCommand();
            if(true === $opts['scale']):
                $command = new ScaleCommand();
            endif;
        endif;

        $cmd = $command->obtainCommand($imagePath, $newPath, $configuration);

        return $cmd;
    }

    function executeCommand($cmd) {
        $c = exec($cmd, $output, $return_code);
        if($return_code != 0) {
            error_log("Tried to execute : $cmd, return code: $return_code, output: " . print_r($output, true));
            throw new RuntimeException();
        }
    }

    public function isNewPathInCache($path, $imagePath) {
        $fileExists = file_exists($path);
        $fileIsMoreRecent = $this->isMoreRecent($path, $imagePath);

        return $fileExists && !$fileIsMoreRecent;
    }

    function isMoreRecent($path, $imagePath) {
        $origFileTime = date("YmdHis", filemtime($imagePath));
        $newFileTime = date("YmdHis", filemtime($path));
        $isMoreRecent = $newFileTime < $origFileTime;
        return $isMoreRecent;
    }
}