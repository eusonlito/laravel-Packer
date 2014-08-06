<?php
namespace Laravel\Packer\Providers;

use JSMin;

class JS extends ProviderBase implements ProviderInterface
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

        return JSMin::minify(file_get_contents($file));
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
        $attributes['src'] = asset($file);

        return '<script '.$this->attributes($attributes).'></script>'.PHP_EOL;
    }
}
