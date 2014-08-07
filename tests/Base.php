<?php
use Laravel\Packer\Packer;
use org\bovigo\vfs\vfsStream as fs;

abstract class Base extends PHPUnit_Framework_TestCase
{
    protected $Packer;
    protected $FS;
    protected $files = [];

    public function setUp()
    {
        $config = require (__DIR__.'/../src/config/config.php');

        $config['asset'] = 'http://'.gethostname().'.com/';
        $config['environment'] = 'testing';
        $config['base_folder'] = '/cache/';
        $config['ignore_environemnts'] = ['local'];

        $this->FS = fs::setup('public');

        $js = fs::newDirectory('js')->at($this->FS);
        $css = fs::newDirectory('css')->at($this->FS);

        foreach (glob(__DIR__.'/resources/*') as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            $new = fs::newFile(basename($file))
                ->setContent(file_get_contents($file))
                ->at($$ext);
        }

        $config['public_path'] = $this->FS->url();

        $this->cache = $this->FS->url().$config['base_folder'];

        $this->Packer = new Packer($config);
    }

    public function checkContents($file, array $tests)
    {
        $file = file_get_contents($file);

        foreach ($tests as $test) {
            $this->assertTrue(strstr($file, $test) ? true : false);
        }
    }
}