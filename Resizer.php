<?php


class Resizer {

    private $path;
    private $configuration;

    public function __construct($path, $configuration=null) {
        if ($configuration == null) $configuration = new Configuration();
        $this->checkPath($path);
        $this->checkConfiguration($configuration);
        $this->path = $path;
    }

    public function obtainFilePath() {
        if($path->isHttpProtocol()):
            $filename = $path->obtainFileName();
            $local_filepath = $configuration->obtainRemote() .$filename;
            $download_image = true;
            if(file_exists($local_filepath)):
                if(filemtime($local_filepath) < strtotime('+'.$opts['cache_http_minutes'].' minutes')):
                    $download_image = false;
                endif;
            endif;
            if($download_image == true):
                $img = file_get_contents($imagePath);
                file_put_contents($local_filepath,$img);
            endif;
            $imagePath = $local_filepath;
        endif;
    }

    private function checkPath($path) {
        if (!($path instanceof ImagePath)) throw new InvalidArgumentException();
    }

    private function checkConfiguration($configuration) {
        if (!($configuration instanceof Configuration)) throw new InvalidArgumentException();
    }
}