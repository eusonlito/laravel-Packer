<?php
namespace Laravel\Packer\Providers;

class JS extends ProviderBase implements ProviderInterface
{
    /**
     *  The extension of the outputted file.
     */
    const EXTENSION = '.js';

    /**
     * @return string
     */
    public function packer()
    {
        return $this->put(JSMin::packer($this->appended));
    }

    /**
     * @param $file
     * @param array $attributes
     * @return string
     */
    public function tag($file, array $attributes)
    {
        $attributes['src'] = $file;

        return '<script'.$this->attributes($attributes).'></script>'.PHP_EOL;
    }
}
