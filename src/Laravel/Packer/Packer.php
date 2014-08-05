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
    protected $files = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $storage;

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
    public function load($type, $files, $name)
    {
        $this->files = is_array($files) ? $files : [$files];

        if (preg_match('/\.'.$type.'$/i', $name)) {
            $this->storage = dirname($name).'/';
            $this->name = basename($name);
        } else {
            $this->storage = str_replace('//', '/', $name.'/');
            $this->name = md5(implode('', $this->files)).'.'.$type;
        }

        $this->file = str_replace('//', '/', public_path($this->storage.$this->name));

        return $this->process($files);
    }
	
    /**
     * @param mixed $files
     * @throws Exceptions\Exception
     * @return this
     */
    private function process($files)
    {
        if ($this->local()) {
            return $this;
        }

        if (is_file($this->file)) {
            return $this;
        }

        $this->checkDir(dirname($this->file));

        $fp = fopen($this->file, 'w');

        foreach ($this->files as $file) {
            $real = public_path($file);

            if (!is_file($real)) {
                throw new Exception(sprintf('File "%s" not exists', $real));
            }

            fwrite($fp, $this->provider->pack($real, $file));
        }

        fclose($fp);

        return $this;
    }

    /**
     * @param string $dir
     * @return boolean
     */
    private function checkDir($dir)
    {
        if (is_dir($dir)) {
            return true;
        }

        return mkdir($dir, 0755, true);
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->local()) {
            $list = $this->files;
        } else {
            $list = $this->storage.$this->name;
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
        if (!isset($config['ignore_environments']) || !is_array($config['ignore_environments'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'ignore_environments'));
        }

        return $config;
    }
}