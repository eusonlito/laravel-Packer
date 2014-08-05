<?php
namespace Laravel\Packer\Providers;

use Laravel\Packer\Processors\CSSmin;

class CSS extends ProviderBase implements ProviderInterface
{
    /**
     * @param string $file
     * @param string $base
     * @return string
     */
    public function pack($file, $base = '')
    {
        $contents = (new CSSmin)->run(file_get_contents($file));
        dd($contents);
        return preg_replace('/(url\([\'"]?)/', '$1'.$base.dirname($file).'/', $contents);
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

        $attributes['href'] = $file;
        $attributes['rel'] = 'stylesheet';

        return '<link '.$this->attributes($attributes).' />'.PHP_EOL;
    }
}
