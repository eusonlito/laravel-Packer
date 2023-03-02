<?php
namespace Eusonlito\LaravelPacker\Providers;

use Eusonlito\LaravelPacker\Processors\CSS\Minify;

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

        $contents = $this->contents($file);

        return preg_replace('/(url\([\'"]?)/', '$1'.$this->settings['asset'].dirname($public).'/', $contents);
    }

    /**
     * @param  string $file
     * @return string
     */
    protected function contents($file)
    {
        $contents = file_get_contents($file);

        if (empty($this->settings['minify'])) {
            return $contents;
        }

        $minify = new Minify();
        $minify->removeImportantComments(true);

        return $minify->run($contents);
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
