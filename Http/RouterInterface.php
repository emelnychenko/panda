<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

/**
 *  Http Router Interface
 *
 *  @subpackage Http
 */
interface RouterInterface
{
    /**
     *  Register route with pattern rules.
     *
     *  @var mixed $pattern
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function add($pattern, $url, $handler = null);

    /**
     *  Register route for all methods.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function any($url, $handler = null);

    /**
     *  Register route for GET method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function get($url, $handler = null);

    /**
     *  Register route for POST method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function post($url, $handler = null);

    /**
     *  Register route for PUT method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function put($url, $handler = null);

    /**
     *  Register route for DELETE method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function delete($url, $handler = null);

    /**
     *  Register route for DENIED method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function deny($url = '*', $handler = null);

    /**
     *  Append processors.
     *
     *  @var array $guard
     *
     *  @return \Panda\Http\Router
     */
    public function guard(array $guard);

    /**
     *  Proceed group.
     *
     *  @var array $group
     *  @var Closure $group
     *
     *  @return \Panda\Http\Router
     */
    public function group(array $group, callable $callback);

    /**
     *  Dispatch router logic.
     *
     *  @return mixed
     */
    public function run();
}
