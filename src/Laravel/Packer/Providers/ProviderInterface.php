<?php
namespace Laravel\Packer\Providers;

interface ProviderInterface {

    /**
     * @return mixed
     */
    public function packer();

    /**
     * @param $file
     * @param $attributes
     * @return mixed
     */
    public function tag($file, array $attributes);
}
