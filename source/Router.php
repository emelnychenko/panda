<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

use Panda\Foundation\RoutingDispatchAbstract;
use Panda\Foundation\ProviderSingletonInterface;

/**
 *  Http Router
 *
 *  @subpackage Http
 */
class Router extends RoutingDispatchAbstract implements RouterInterface, ProviderSingletonInterface 
{
    /**
     *  Factory layer.
     *
     *  @var \Panda\Http\Request $request
     *
     *  @return \Panda\Http\Router
     */
    public static function factory(Request $request)
    {
        return new static($request);
    }

    /**
     *  Singleton layer.
     *
     *  @return \Panda\Http\Router
     */
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
