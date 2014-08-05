<?php
namespace Laravel\Packer\Providers;

use Laravel\Packer\Processors\JSMin;

class JS extends ProviderBase implements ProviderInterface
{
    /**
     * @param string $file
     * @param string $public
     * @return string
     */
    public function pack($file, $public)
    {
        return JSMin::minify($file);
    }

    /**
     * @param mixed $file
     * @param array $attributes
     * @return string
     */
    public function tag($file, array $attributes = array())
    {
        if (is_array($file)) {
            return $this->tags($file, $attributes);
        }

        $attributes['src'] = asset($file);

        return '<script '.$this->attributes($attributes).'></script>'.PHP_EOL;
    }
}
