<?php
namespace Laravel\Packer;

use Laravel\Packer\Providers\JS;
use Laravel\Packer\Providers\CSS;
use Laravel\Packer\Providers\IMG;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use DirectoryIterator;
use IteratorIterator;
use RegexIterator;

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
     * @param  array                      $config
     * @throws Exceptions\InvalidArgument
     * @return this
     */
    public function __construct(array $config)
    {
        return $this->setConfig($config);
    }

    /**
     * @param  array                      $config
     * @throws Exceptions\InvalidArgument
     * @throws Exceptions\DirNotExist
     * @return this
     */
    public function setConfig(array $config)
    {
        $config = array_merge($this->config, $config);

        if (!isset($config['ignore_environments']) || !is_array($config['ignore_environments'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'ignore_environments'));
        }

        if (!isset($config['cache_folder'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'cache_folder'));
        }

        if (!isset($config['check_timestamps'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'check_timestamps'));
        }

        if (!isset($config['css_minify'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'css_minify'));
        }

        if (!isset($config['js_minify'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'js_minify'));
        }

        if (!isset($config['environment'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'environment'));
        }

        if (!isset($config['asset'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'asset'));
        }

        if (!isset($config['public_path'])) {
            throw new Exceptions\InvalidArgument(sprintf('Missing option %s', 'public_path'));
        }

        if (!is_dir($config['public_path'])) {
            throw new Exceptions\DirNotExist(sprintf('Folder %s not exists', $config['public_path']));
        }

        $this->config = $config;

        return $this;
    }

    /**
     * @param  mixed  $files
     * @param  string $name
     * @param  array  $attributes
     * @return this
     */
    public function js($files, $name, array $attributes = [])
    {
        $this->provider = new Js([
            'asset' => $this->config['asset'],
            'minify' => $this->config['js_minify'],
            'attributes' => $attributes
        ]);

        return $this->load('js', $files, $name);
    }

    /**
     * @param  mixed   $dir
     * @param  string  $name
     * @param  boolean $recursive
     * @param  array   $attributes
     * @return this
     */
    public function jsDir($dir, $name, $recursive = false, array $attributes = [])
    {
        $this->provider = new JS([
            'asset' => $this->config['asset'],
            'minify' => $this->config['js_minify'],
            'attributes' => $attributes
        ]);

        return $this->load('js', $this->scanDir('js', $dir, $recursive), $name);
    }

    /**
     * @param  mixed  $files
     * @param  string $name
     * @param  array  $attributes
     * @return this
     */
    public function css($files, $name, array $attributes = [])
    {
        $this->provider = new CSS([
            'asset' => $this->config['asset'],
            'minify' => $this->config['css_minify'],
            'attributes' => $attributes
        ]);

        return $this->load('css', $files, $name);
    }

    /**
     * @param  mixed   $dir
     * @param  string  $name
     * @param  boolean $recursive
     * @param  array   $attributes
     * @return this
     */
    public function cssDir($dir, $name, $recursive = false, array $attributes = [])
    {
        $this->provider = new CSS([
            'asset' => $this->config['asset'],
            'minify' => $this->config['css_minify'],
            'attributes' => $attributes
        ]);

        return $this->load('css', $this->scanDir('css', $dir, $recursive), $name);
    }

    /**
     * @param  string                     $file
     * @param  string                     $transform
     * @param  string                     $name
     * @param  array                      $attributes
     * @throws Exceptions\InvalidArgument
     * @return this
     */
    public function img($file, $transform, $name = '', array $attributes = [])
    {
        if (!is_string($file)) {
            throw new Exceptions\InvalidArgument('img function only supports strings');
        }

        $valid = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, $valid, true)) {
            throw new Exceptions\InvalidArgument('Only jpg/jpeg/png/gif files are supported as valid images');
        }

        $name = $name ?: 'images/'.md5($file).'.'.$ext;
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
            'asset' => $this->config['asset'],
            'transform' => $transform,
            'attributes' => $attributes
        ]);

        return $this->load($ext, $file, $name);
    }

    /**
     * @param  string                 $ext
     * @param  string                 $dir
     * @param  boolean                $recursive
     * @throws Exceptions\DirNotExist
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
            throw new Exceptions\DirNotExist(sprintf('Folder %s not exists', $dir));
        }

        if ($recursive) {
            $Iterator = new RecursiveDirectoryIterator($dir);
            $Iterator = new RegexIterator(new RecursiveIteratorIterator($Iterator), '/\.'.$ext.'$/i', RegexIterator::MATCH);
        } else {
            $Iterator = new DirectoryIterator($dir);
            $Iterator = new RegexIterator(new IteratorIterator($Iterator), '/\.'.$ext.'$/i', RegexIterator::MATCH);
        }

        $public = $this->path('public');

        foreach ($Iterator as $file) {
            $files[] = str_replace($public, '', $file->getPathname());
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
            $name = $this->path('', $this->config['cache_folder'].'/'.$name);
        }

        if (preg_match('/\.'.$type.'$/i', $name)) {
            $this->storage = dirname($name).'/';
            $this->name = basename($name);
        } else {
            $this->storage = $this->path('', $name.'/');
            $this->name = md5(implode('', $this->files)).'.'.$type;
        }

        if ($this->files && !$this->isLocal() && ($this->config['check_timestamps'] === true)) {
            $this->newer = max(array_map(function ($file) {
                if (is_file($file = $this->path('public', $file))) {
                    return filemtime($file);
                }
            }, $this->files));

            $this->name = $this->newer.'-'.$this->name;
        }

        $this->file = $this->path('public', $this->storage.$this->name);

        return $this->process();
    }

    /**
     * @param  string                     $name
     * @param  string                     $location
     * @throws Exceptions\InvalidArgument
     * @return string
     */
    public function path($name, $location = '')
    {
        if ($name === '') {
            $path = '';
        } elseif ($name === 'public') {
            $path = $this->config['public_path'];
        } else {
            throw new Exceptions\InvalidArgument(sprintf('This path does not exists %s', $name));
        }

        return preg_replace('#(^|[^:])//+#', '$1/', $path.'/'.$location);
    }

    /**
     * @throws Exceptions\FileNotWritable
     * @return this
     */
    private function process()
    {
        if ($this->useCache()) {
            return $this;
        }

        $this->checkDir(dirname($this->file));

        if (!($fp = @fopen($this->file, 'c'))) {
            throw new Exceptions\FileNotWritable(sprintf('File %s can not be created', $name));
        }

        foreach ($this->files as $file) {
            fwrite($fp, $this->provider->pack($this->path('public', $file), $file));
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
     * @param  string                    $dir
     * @throws Exceptions\DirNotWritable
     * @return boolean
     */
    private function checkDir($dir)
    {
        if (is_dir($dir)) {
            return true;
        }

        if (!@mkdir($dir, 0755, true)) {
            throw new Exceptions\DirNotWritable(sprintf('Folder %s can not be created', $dir));
        }
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
     * @return boolean
     */
    public function getFilePublic()
    {
        return $this->storage.$this->name;
    }

    /**
     * @return boolean
     */
    public function getFilePath()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}
