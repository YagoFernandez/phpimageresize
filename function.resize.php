<?php

require 'ImagePath.php';
require 'Configuration.php';
require 'Resizer.php';

function resize($imagePath,$opts=null){

	$path = new ImagePath($imagePath);
	$configuration = new Configuration($opts);
	$resizer = new Resizer($path, $configuration);

	$newPath = $resizer->doResize();
	$cacheFilePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);

	return $cacheFilePath;
}

