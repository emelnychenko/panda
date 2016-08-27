<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Http;

use Panda\Foundation\Http\RoutingDispatchAbstract;

/**
 *  Http Router
 *
 *  @subpackage Http
 */
class Router extends RoutingDispatchAbstract implements RouterInterface
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
     *  @var \Panda\Http\Request $request
     *
     *  @return \Panda\Http\Router
     */
    public static function singleton(Request $request = null)
    {
        static $instance = null;

        if (
            is_null($instance)
        ) {
            $instance = new static($request);
        }

        return $instance;
    }
}
