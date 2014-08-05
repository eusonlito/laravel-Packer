<?php
namespace Laravel\Packer\Providers;

interface ProviderInterface {

    /**
     * @param string $file
     * @param string $base
     * @return mixed
     */
    public function pack($file, $base);

    /**
     * @param $file
     * @param $attributes
     * @return mixed
     */
    public function tag($file, array $attributes);
}
