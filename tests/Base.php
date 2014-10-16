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
        $config['cache_folder'] = '/cache/';
        $config['ignore_environemnts'] = ['local'];

        $fs = fs::setup('public');

        $resources = fs::newDirectory('resources')->at($fs);

        $js = fs::newDirectory('js')->at($resources);
        $css = fs::newDirectory('css')->at($resources);
        $img = fs::newDirectory('img')->at($resources);

        foreach (glob(__DIR__.'/resources/*') as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $ext = in_array($ext, ['css', 'js'], true) ? $ext : 'img';

            $new = fs::newFile(basename($file))
                ->setContent(file_get_contents($file))
                ->at($$ext);
        }

        $config['public_path'] = $fs->url();

        $this->cache = $config['public_path'].$config['cache_folder'];

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
