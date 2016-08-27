<?php

namespace Panda;

use Panda\Foundation\ProviderSingletonInterface;

class Support implements SupportInterface, ProviderSingletonInterface
{
    protected $history = array();
    protected $provide = array();

    public function __construct() {}

    public function append($invoke, $service = null)
    {
        if (
            is_array($invoke) && $service === null
        ) {
            foreach (
                $invoke as $_invoke => $_service
            ) {
                $this->__append($_invoke, $_service);
            }
        } else {
            $this->__append($invoke, $service);
        }

        return $this;
    }   

    protected function __append($invoke, $service)
    {
        if (is_object($service)) {
            $this->history[get_class($service)] = $invoke; 
        }

        $this->history[$invoke] = $invoke;
        $this->provide[$invoke] = $service;
    }

    public function get($invoke)
    {
        if (
            array_key_exists($invoke, $this->history)
        ) {
            return $this->provide[$this->history[$invoke]];
        }

        return null;
    }

    public static function singleton()
    {
        static $instance = null;

        if (
            is_null($instance)
        ) {
            $instance = new static();
        }

        return $instance;
    }
}