<?php
namespace Eusonlito\LaravelPacker\Providers;

use CSSmin;

class CSS extends ProviderBase implements ProviderInterface
{
    /**
     * @param  string $file
     * @param  string $public
     * @return string
     */
    public function pack($file, $public)
    {
        if (!is_file($file)) {
            return sprintf('/* File %s not exists */', $file);
        }

        $contents = file_get_contents($file);

        if ($this->settings['minify']) {
            $contents = (new CSSmin())->run($contents);
        }

        return preg_replace('/(url\([\'"]?)/', '$1'.$this->settings['asset'].dirname($public).'/', $contents);
    }

    /**
     * @param  mixed  $file
     * @return string
     */
    public function tag($file)
    {
        if (is_array($file)) {
            return $this->tags($file);
        }

        $attributes = $this->settings['attributes'];
        $attributes['href'] = $this->path($this->settings['asset'].$file);
        $attributes['rel'] = 'stylesheet';

        return '<link '.$this->attributes($attributes).' />'.PHP_EOL;
    }
}
