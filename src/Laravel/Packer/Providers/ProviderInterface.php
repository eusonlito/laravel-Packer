<?php
namespace Laravel\Packer\Providers;

interface ProviderInterface {

    /**
     * @param string $file
     * @param string $public
     * @return mixed
     */
    public function pack($file, $public);

    /**
     * @param mixed $file
     * @param array $attributes
     * @return mixed
     */
    public function tag($file, array $attributes);
}
