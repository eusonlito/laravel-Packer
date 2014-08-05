<?php
namespace Laravel\Packer\Providers;

interface ProviderInterface
{
    /**
     * @param  string $file
     * @param  string $base
     * @return mixed
     */
    public function pack($file, $base);

    /**
     * @param  string $file
     * @param  array  $attributes
     * @return mixed
     */
    public function tag($file, array $attributes);
}
