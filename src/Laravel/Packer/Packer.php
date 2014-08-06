<?php
namespace Laravel\Packer;

use Laravel\Packer\Providers\JS;
use Laravel\Packer\Providers\CSS;
use Laravel\Packer\Providers\IMG;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use RegexIterator;

use InvalidArgumentException;
use Exception;

class Packer
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $paths = [];

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $storage = '';

    /**
     * @var integer
     */
    private $newer = 0;

    /**
     * @var array
     */
    private $force = [
        'current' => false,
        'previous' => false
    ];

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var object
     */
    private $provider;

    /**
     * @param  array                               $config
     * @throws Exceptions\InvalidArgumentException
     */
    public function __construct(array $config)
    {
        if (!isset($config['ignore_environments']) || !is_array($config['ignore_environments'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'ignore_environments'));
        }

        if (!isset($config['base_folder'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'base_folder'));
        }

        if (!isset($config['check_timestamps'])) {
            throw new InvalidArgumentException(sprintf('Missing option %s', 'check_timestamps'));
        }

        $this->config = $config;
    }

    /**
     * @param  mixed  $files
     * @param  string $name
     * @return this
     */
    public function js($files, $name, array $attributes = [])
    {
        $this->provider = new Js([
            'attributes' => $attributes
        ]);

        return $this->load('js', $files, $name);
    }

    /**
     * @param  mixed   $dir
     * @param  string  $name
     * @param  boolean $recursive
     * @return this
     */
    public function jsDir($dir, $name, $recursive = false)
    {
        $this->provider = new JS();

        return $this->load('js', $this->scanDir('js', $dir, $recursive), $name);
    }

    /**
     * @param  mixed  $files
     * @param  string $name
     * @return this
     */
    public function css($files, $name, array $attributes = [])
    {
        $this->provider = new CSS([
            'attributes' => $attributes
        ]);

        return $this->load('css', $files, $name);
    }

    /**
     * @param  mixed   $dir
     * @param  string  $name
     * @param  boolean $recursive
     * @return this
     */
    public function cssDir($dir, $name, $recursive = false)
    {
        $this->provider = new CSS();

        return $this->load('css', $this->scanDir('css', $dir, $recursive), $name);
    }

    /**
     * @param  string                              $file
     * @param  string                              $name
     * @param  string                              $transform
     * @param  array                               $attributes
     * @throws Exceptions\InvalidArgumentException
     * @return this
     */
    public function img($file, $name, $transform, array $attributes = [])
    {
        if (!is_string($file)) {
            throw new InvalidArgumentException('img function only supports strings');
        }

        $valid = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $valid, true)) {
            throw new InvalidArgumentException('Only jpg/jpeg/png/gif files are supported as valid images');
        }

        $md5 = md5($transform).'/';

        if (preg_match('/\.('.implode('|', $valid).')$/i', $name)) {
            $name = dirname($name).'/'.$md5.basename($name);
        } elseif (substr($name, -1) === '/') {
            $name .= $md5;
        } else {
            $name .= '/'.$md5;
        }

        $this->force['previous'] = $this->force['current'];
        $this->force['current'] = true;

        $this->provider = new IMG([
            'transform' => $transform,
            'attributes' => $attributes
        ]);

        $src = $this->load($ext, $file, $name);

        return $src;
    }

    /**
     * @param  string                              $ext
     * @param  string                              $dir
     * @param  boolean                             $recursive
     * @throws Exceptions\InvalidArgumentException
     * @return array
     */
    private function scanDir($ext, $dir, $recursive = false)
    {
        $files = [];

        if (is_array($dir)) {
            foreach ($dir as $each) {
                $files = array_merge($files, $this->scanDir('css', $each, $recursive));
            }

            return $files;
        }

        $dir = $this->path('public', $dir);

        if (!is_dir($dir)) {
            throw new InvalidArgumentException(sprintf('Folder %s not exists', $dir));
        }

        if ($recursive) {
            $Iterator = new RecursiveDirectoryIterator($dir);
            $Iterator = new RegexIterator(new RecursiveIteratorIterator($Iterator), '/\.'.$ext.'$/i', RegexIterator::MATCH);
        } else {
            $Iterator = glob($dir.'*\.'.$ext);
        }

        $public = $this->path('public');

        foreach ($Iterator as $file) {
            $files[] = str_replace($public, '', $file);
        }

        return $files;
    }

    /**
     * @param  string $type
     * @param  mixed  $file
     * @param  string $name
     * @return this
     */
    public function load($type, $files, $name)
    {
        $this->files = is_array($files) ? $files : [$files];

        if (strpos($name, '/') !== 0) {
            $name = $this->path('', $this->config['base_folder'].'/'.$name);
        }

        if (preg_match('/\.'.$type.'$/i', $name)) {
            $this->storage = dirname($name).'/';
            $this->name = basename($name);
        } else {
            $this->storage = $this->path('', $name.'/');
            $this->name = md5(implode('', $this->files)).'.'.$type;
        }

        if (!$this->isLocal() && ($this->config['check_timestamps'] === true)) {
            $this->newer = max(array_map(function ($file) {
                if (is_file($file = $this->path('public', $file))) {
                    return filemtime($file);
                }
            }, $this->files));

            $this->name = $this->newer.'-'.$this->name;
        }

        $this->file = $this->path('public', $this->storage.$this->name);

        return $this->process($files);
    }

    /**
     * @param  string $name
     * @param  string $location
     * @return string
     */
    private function path($name, $location = '')
    {
        if (!array_key_exists($name, $this->paths)) {
            $this->setPath($name);
        }

        $path = str_replace('//', '/', $this->paths[$name].'/'.$location);

        if ($name === 'asset') {
            $path = str_replace(':/', '://', $path);
        }

        return $path;
    }

    /**
     * @param  string                              $name
     * @throws Exceptions\InvalidArgumentException
     * @return string
     */
    private function setPath($name)
    {
        switch ($name) {
            case '':
                return $this->paths[$name] = '';

            case 'public':
                return $this->paths[$name] = public_path();

            case 'asset':
                return $this->paths[$name] = asset('');
        }

        throw new InvalidArgumentException(sprintf('This path does not exists %s', $name));
    }

    /**
     * @param  mixed                $files
     * @throws Exceptions\Exception
     * @return this
     */
    private function process($files)
    {
        if ($this->useCache()) {
            return $this;
        }

        $this->checkDir(dirname($this->file));

        $fp = fopen($this->file, 'c');

        foreach ($this->files as $file) {
            $real = $this->path('public', $file);

            if (is_file($real)) {
                fwrite($fp, $this->provider->pack($real, $file));
            }
        }

        fclose($fp);

        return $this;
    }

    /**
     * @return boolean
     */
    private function useCache()
    {
        if ($this->isLocal()) {
            return true;
        } if (!is_file($this->file)) {
            return false;
        } if ($this->config['check_timestamps'] === false) {
            return true;
        }

        return ($this->newer < filemtime($this->file));
    }

    /**
     * @param  string  $dir
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
        if ($this->isLocal()) {
            $list = $this->files;
        } else {
            $list = $this->storage.$this->name;
        }

        $this->force['current'] = $this->force['previous'];

        return $this->provider->tag($list);
    }

    /**
     * @return boolean
     */
    protected function isLocal()
    {
        return ($this->force['current'] === false) && in_array($this->config['environment'], $this->config['ignore_environments'], true);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
