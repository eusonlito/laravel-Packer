<?php
namespace Laravel\Packer\Providers;

use Laravel\Packer\Processors\CSSmin;

class CSS extends ProviderBase implements ProviderInterface
{
    /**
     * @param string $file
     * @param string $public
     * @return string
     */
    public function pack($file, $public)
    {
        $contents = (new CSSmin)->run(file_get_contents($file));
        return preg_replace('/(url\([\'"]?)/', '$1'.asset(dirname($public)).'/', $contents);
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

        $attributes['href'] = asset($file);
        $attributes['rel'] = 'stylesheet';

        return '<link '.$this->attributes($attributes).' />'.PHP_EOL;
    }
}
