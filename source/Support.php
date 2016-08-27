<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\SingletonProviderInterface;
use Panda\Foundation\SingletonProviderExpansion;

/**
 *  Panda Support
 *
 *  @subpackage Framework
 */
class Support implements SupportInterface, SingletonProviderInterface
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

    use SingletonProviderExpansion;
}