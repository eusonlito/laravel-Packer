<?php
namespace Laravel\Packer\Providers;

use Laravel\Packer\Processors\CSSMin;

class CSS extends ProviderBase implements ProviderInterface
{
    /**
     *  The extension of the outputted file.
     */
    const EXTENSION = '.css';

    /**
     * @return string
     */
    public function packer()
    {
        return $this->put((new CSSMin($this->appended))->getMinified());
    }

    /**
     * @param $file
     * @param array $attributes
     * @return string
     */
    public function tag($file, array $attributes = array())
    {
        $attributes['href'] = $file;
        $attributes['rel'] = 'stylesheet';

        return '<link'.$this->attributes($attributes).'>'.PHP_EOL;
    }
}
