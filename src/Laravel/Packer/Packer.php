<?php
namespace Laravel\Packer; 

use Laravel\Packer\Providers\JS;
use Laravel\Packer\Providers\CSS;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use InvalidArgumentException;
use Exception;

class Packer
{
    /**
     * @var array
     */
    protected $file = [];

    /**
     * @var array
     */
    protected $dir = [];

    /**
     * @var array
     */
    protected $config;

    /**
     * @var
     */
    private $provider;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $this->config($config);
    }

    /**
     * @param mixed $file
     * @param [string $name]
     * @return this
     */
    public function js($files, $name)
    {
        $this->provider = new Js();
        return $this->load('js', $files, $name);
    }

    /**
     * @param mixed $file
     * @param [string $name]
     * @return this
     */
    public function css($files, $name)
    {
        $this->provider = new CSS();
        return $this->load('css', $files, $name);
    }

    /**
     * @param string $type
     * @param mixed $file
     * @param string $name
     * @return this
     */
    public function load($type, $files, $name = '')
    {
        $this->dir['build'] = $this->config[$type.'_build_path'];
        $this->dir['full'] = str_replace('//', '/', public_path($this->dir['build']));

        $this->file['list'] = is_array($files) ? $files : [$files];
        $this->file['name'] = $this->name($name, $type);
        $this->file['full'] = str_replace('//', '/', $this->dir['full'].$this->file['name']);

        return $this->process($files);
    }

    /**
     * @param string $name
     * @param string $ext
     * @return string
     */
    public function name($name, $ext)
    {
        return $name ?: md5(implode('', $this->files['list'])).'.'.$ext;
    }
	
    /**
     * @param mixed $files
     * @return this
     */
    private function process($files)
    {
        if ($this->local()) {
            return $this;
        }

        if (is_file($this->file['full'])) {
            return $this;
        }

        $this->checkDir($this->dir['full']);

        $public = public_path();
        $base = asset('');

        $fp = fopen($this->file['full'], 'w');

        foreach ($this->file['list'] as $file) {
            $file = $public.$file;

            if (!is_file($file)) {
                throw new Exception(sprintf('File "%s" not exists', $file));
            }

            fwrite($fp, $this->provider->pack($file, $base));
        }

        fclose($fp);

        return $this;
    }

    /**
     * @param string $dir
     * @return null
     */
    private function checkDir($dir)
    {
        if (is_dir($dir)) {
            return;
        }

        if (!mkdir($dir, 0755, true)) {
            throw new Exception(sprintf('Folder %s couldn\'t be created', $dir));
        }
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $asset = asset('');

        if ($this->local()) {
            $list = array_map(function ($value) use ($asset) {
                return $asset.$value;
            }, $this->file['list']);
        } else {
            $list = $asset.$this->dir['build'].$this->file['name'];
        }

        return $this->provider->tag($list);
    }

	/**
	 * @return bool
	 */
	protected function local()
	{
		return !in_array($this->config['environment'], $this->config['ignore_environments'], true);
	}

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param array $config
     * @throws Exceptions\InvalidArgumentException
     * @return array
     */
    private function config(array $config)
    {
        if (!isset($config['css_build_path']) || !is_string($config['css_build_path'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'css_build_path'));
        }

        if (!isset($config['js_build_path']) || !is_string($config['js_build_path'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'js_build_path'));
        }

        if (!isset($config['ignore_environments']) || !is_array($config['ignore_environments'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'ignore_environments'));
        }

        return $config;
    }
}