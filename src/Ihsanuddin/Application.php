<?php

namespace Ihsanuddin;

use Ihsanuddin\Http\Kernel;

class Application extends Kernel
{
    protected $configs;

    public function __construct(array $configs = array())
    {
        parent::__construct();

        $this->configs = $configs;

        //@todo: try to use $this['key']
    }

    public function getConfig($key)
    {
        if (array_key_exists($key, $this->configs)) {
            return $this->configs[$key];
        }
    }
}