<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\RoutingDispatchAbstract;

use Panda\Foundation\SingletonProviderInterface;
use Panda\Foundation\SingletonProviderExpansion;

/**
 *  Panda Router
 *
 *  @subpackage Framework
 */
class Router extends RoutingDispatchAbstract implements RouterInterface, SingletonProviderInterface 
{
    /**
     *  Factory layer.
     *
     *  @return Panda\Router
     */
    public static function factory()
    {
        return new static();
    }

    use SingletonProviderExpansion;
}
