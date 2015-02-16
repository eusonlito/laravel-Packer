<?php
namespace Laravel\Packer\Providers;

use Imagecow\Image;

class IMG extends ProviderBase implements ProviderInterface
{
    /**
     * @param  string  $file
     * @return boolean
     */
    public function isImage($file)
    {
        $valid = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        return in_array($ext, $valid, true);
    }

    /**
     * @param  string $file
     * @return string
     */
    public function check($file)
    {
        return ($this->isImage($file) && is_file($file)) ? $file : $this->fake();
    }

    /**
     * @return string
     */
    public function fake()
    {
        if (empty($this->settings['fake'])) {
            return false;
        }

        return realpath(__DIR__.'/../../../images/'.rand(1, 8).'.jpg');
    }

    /**
     * @param  string $file
     * @param  string $public
     * @return string
     */
    public function pack($file, $public)
    {
        if (!($file = $this->check($file))) {
            return;
        }

        return Image::create($file)->transform($this->settings['transform'])->getString();
    }

    /**
     * @param  string $file
     * @return string
     */
    public function tag($file)
    {
        $attributes = $this->settings['attributes'];
        $file = is_array($file) ? $file[0] : $file;

        if (empty($attributes)) {
            return $this->path($this->settings['asset'].$file);
        }

        $attributes['src'] = $this->path($this->settings['asset'].$file);

        return '<img '.$this->attributes($attributes).' />'.PHP_EOL;
    }
}
