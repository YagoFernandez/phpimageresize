<?php

require 'ImagePath.php';
require 'Configuration.php';
require 'Resizer.php';

function sanitize($path) {
	return urldecode($path);
}

function isInCache($path, $imagePath) {
	$isInCache = false;
	if(file_exists($path) == true):
		$isInCache = true;
		$origFileTime = date("YmdHis",filemtime($imagePath));
		$newFileTime = date("YmdHis",filemtime($path));
		if($newFileTime < $origFileTime): # Not using $opts['expire-time'] ??
			$isInCache = false;
		endif;
	endif;

	return $isInCache;
}

function composeNewPath($imagePath, $configuration) {

	$fileSystem = new FileSystem();

	$w = $configuration->obtainWidth();
	$h = $configuration->obtainHeight();

	$filename = $fileSystem->file_get_md5($imagePath);
	$ext = $fileSystem->file_get_extension($imagePath);

	$cropSignal = isset($opts['crop']) && $opts['crop'] == true ? "_cp" : "";
	$scaleSignal = isset($opts['scale']) && $opts['scale'] == true ? "_sc" : "";
	$widthSignal = !empty($w) ? '_w'.$w : '';
	$heightSignal = !empty($h) ? '_h'.$h : '';
	$extension = '.'.$ext;

	$newPath = $configuration->obtainCache() .$filename.$widthSignal.$heightSignal.$cropSignal.$scaleSignal.$extension;

	if($opts['output-filename']) {
		$newPath = $opts['output-filename'];
	}

	return $newPath;
}

function resize($imagePath,$opts=null){


	$path = new ImagePath($imagePath);
	$configuration = new Configuration($opts);

	$resizer = new Resizer($path, $configuration);

	// This has to be done in resizer resize

	try {
		$imagePath = $resizer->obtainFilePath();
	} catch (Exception $e) {
		return 'image not found';
	}


	$newPath = composeNewPath($imagePath, $configuration);

    $create = !isInCache($newPath, $imagePath);

	if($create == true):
		try {
			doResize($imagePath, $newPath, $configuration);
		} catch (Exception $e) {
			return 'cannot resize the image';
		}
	endif;

	// The new path must be the return value of resizer resize

	$cacheFilePath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);

	return $cacheFilePath;
	
}

function doResize($imagePath, $newPath, $configuration) {
	$cmd = selectCommand($imagePath, $newPath, $configuration);
	executeCommand($cmd);
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