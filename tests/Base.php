<?php
use Laravel\Packer\Packer;
use org\bovigo\vfs\vfsStream as fs;

abstract class Base extends PHPUnit_Framework_TestCase
{
    protected $Packer;
    protected $files = [];

    public function setUp()
    {
        ini_set('max_execution_time', 0);

        $config = require __DIR__.'/../src/config/config.php';

        $config['asset'] = 'http://'.gethostname().'.com/';
        $config['environment'] = 'testing';
        $config['base_folder'] = '/cache/';
        $config['ignore_environemnts'] = ['local'];

        $fs = fs::setup('public');

        $resources = fs::newDirectory('resources')->at($fs);
        $js = fs::newDirectory('js')->at($resources);
        $css = fs::newDirectory('css')->at($resources);

        foreach (glob(__DIR__.'/resources/*') as $file) {
            $new = fs::newFile(basename($file))
                ->setContent(file_get_contents($file))
                ->at(${strtolower(pathinfo($file, PATHINFO_EXTENSION))});
        }

        $config['public_path'] = $fs->url();

        $this->cache = $config['public_path'].$config['base_folder'];

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
