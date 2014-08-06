<?php
namespace Laravel\Packer\Providers;

use Imagecow\Image;

class IMG extends ProviderBase implements ProviderInterface
{
    /**
     * @param  string $file
     * @param  string $public
     * @return string
     */
    public function pack($file, $public)
    {
        return Image::create($file)->transform($this->settings['transform'])->getString();
    }

    /**
     * @param  string  $file
     * @return string
     */
    public function tag($file, array $attributes = [])
    {
        if (empty($this->settings['attributes'])) {
            return asset($file);
        }

        $attributes = $this->settings['attributes'];
        $attributes['src'] = asset($file);

        return '<img '.$this->attributes($attributes).'/>'.PHP_EOL;
    }
}
