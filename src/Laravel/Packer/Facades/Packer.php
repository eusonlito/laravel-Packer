<?php
namespace Laravel\Packer\Facades;

use Illuminate\Support\Facades\Facade;

class Packer extends Facade
{
    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Packer';
    }
}
