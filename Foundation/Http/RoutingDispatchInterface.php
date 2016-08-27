<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http;

/**
 *  Routing Dispatch Interface
 *
 *  @subpackage Http
 */
interface RoutingDispatchInterface 
{
    /**
     *  Register route with pattern rules.
     *
     *  @var mixed $pattern
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function add($pattern, $url, $handler = null);

    /**
     *  Register route for all methods.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function any($url, $handler = null);

    /**
     *  Register route for GET method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function get($url, $handler = null);

    /**
     *  Register route for POST method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function post($url, $handler = null);

    /**
     *  Register route for PUT method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function put($url, $handler = null);

    /**
     *  Register route for DELETE method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function delete($url, $handler = null);

    /**
     *  Register route for DENIED method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function deny($url = '*', $handler = null);

    /**
     *  Append processors.
     *
     *  @var array $processors
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function processor(array $processors);

    /**
     *  Proceed group.
     *
     *  @var array $group
     *  @var Closure $group
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function group(array $group, Closure $callback);

    /**
     *  Dispatch router logic.
     *
     *  @return mixed
     */
    public function run();
}

