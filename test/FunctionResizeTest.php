<?php

include 'Configuration.php';

class FunctionResizeTest extends PHPUnit_Framework_TestCase {

    private $defaults = array(
        'crop' => false,
        'scale' => 'false',
        'thumbnail' => false,
        'maxOnly' => false,
        'canvas-color' => 'transparent',
        'output-filename' => false,
        'cacheFolder' => './cache/',
        'remoteFolder' => './cache/remote/',
        'quality' => 90,
        'cache_http_minutes' => 20,
        'width' => 1,
        'height' => 1
    );

    public function testOpts()
    {
        $this->assertInstanceOf('Configuration', new Configuration);
    }

    public function testNullOptsDefaults() {
        $configuration = new Configuration(null);

        $this->assertEquals($this->defaults, $configuration->asHash());
    }

    public function testDefaults() {
        $configuration = new Configuration();
        $asHash = $configuration->asHash();

        $this->assertEquals($this->defaults, $asHash);
    }

    public function testDefaultsNotOverwriteConfiguration() {

        $opts = array(
            'thumbnail' => true,
            'maxOnly' => true
        );

        $configuration = new Configuration($opts);
        $configured = $configuration->asHash();

        $this->assertTrue($configured['thumbnail']);
        $this->assertTrue($configured['maxOnly']);
    }

    public function testObtainCache() {
        $configuration = new Configuration();

        $this->assertEquals('./cache/', $configuration->obtainCache());
    }

    public function testObtainRemote() {
        $configuration = new Configuration();

        $this->assertEquals('./cache/remote/', $configuration->obtainRemote());
    }

    public function testObtainConvertPath() {
        $configuration = new Configuration();

        $this->assertEquals('convert', $configuration->obtainConvertPath());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNoMinimumParameters() {
        $opts = array(
            'output-filename' => null,
            'width' => null,
            'height' => null
        );

        $configuration = new Configuration($opts);
    }


    public function composeNewPath($imagePath, $configuration) {

        $fileSystem = new FileSystem();
        $opts = $configuration->asHash();

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

    public function testComposeNewPathWithoutOutputFileName() {

        $configuration = new Configuration();
        $filename = "https://upload.wikimedia.org/wikipedia/commons/e/ea/Sydney_Harbour_Bridge_night.jpg";
        $expected = "./cache/ffd81ed315aabd739b7f0e6c1d76c697_w1_h1_sc..jpg";
        $this->assertEquals($expected, $this->composeNewPath($filename, $configuration));
    }
}

?>
