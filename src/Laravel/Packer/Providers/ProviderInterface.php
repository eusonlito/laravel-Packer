<?php
namespace Laravel\Packer\Providers;

interface ProviderInterface
{
    /**
     * @param  array  $settings
     * @return string
     */
    public function __construct(array $settings);

    /**
     * @param  string $file
     * @param  string $base
     * @return mixed
     */
    public function pack($file, $base);

    /**
     * @param  string $file
     * @return mixed
     */
    public function tag($file);
}
