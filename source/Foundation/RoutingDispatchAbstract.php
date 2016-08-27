<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

use Closure;

/**
 *  Routing Dispatch Abstract
 *
 *  @subpackage Foundation
 */
abstract class RoutingDispatchAbstract
{
    /**
     *  @var \Panda\Foundation\Http\EssenceImplementation\EssenceRoutes
     */ 
    protected $routes;

    /**
     *  @var \Panda\Foundation\Http\EssenceImplementation\EssenceRoutes
     */ 
    protected $denied;

    /**
     *  @var string
     */ 
    protected $method;

    /**
     *  @var \Panda\Foundation\Http\EssenceImplementation\EssenceProcessors
     */ 
    protected $processors;

    /**
     *  @var array
     */ 
    protected $alinement = array();

    /**
     *  Get headers Essence, value or set header.
     *
     *  @return mixed
     */
    public function __construct()
    {
        $this->routes       = new EssenceWriteableInstance();
        $this->denied       = new EssenceWriteableInstance();
        $this->processors   = new EssenceWriteableInstance();
        $this->method       = array_key_exists('REQUEST_METHOD', $_SERVER) ? $_SERVER['REQUEST_METHOD'] : 'GET';
    }

    /**
     *  Register route with pattern rules.
     *
     *  @var mixed $pattern
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function add($pattern, $url, $handler = null)
    {
        $matches = is_array($pattern) ? $pattern : explode('|', $pattern);

        if (
            in_array($this->method, $matches, true)
        ) {
            $this->iterator(
                $url, $handler, array($this, 'prepare'), 'routes'
            );
        }

        return $this;
    }

    /**
     *  Register route for all methods.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function any($url, $handler = null)
    {
        return $this->add(array('GET', 'POST', 'PUT', 'DELETE'), $url, $handler);
    }

    /**
     *  Register route for GET method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function get($url, $handler = null)
    {
        return $this->add(array('GET'), $url, $handler);
    }

    /**
     *  Register route for POST method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function post($url, $handler = null)
    {
        return $this->add(array('POST'), $url, $handler);
    }

    /**
     *  Register route for PUT method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function put($url, $handler = null)
    {
        return $this->add(array('PUT'), $url, $handler);
    }

    /**
     *  Register route for DELETE method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function delete($url, $handler = null)
    {
        return $this->add(array('DELETE'), $url, $handler);
    }

    /**
     *  Register route for DENIED method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function deny($url = '*', $handler = null)
    {
        $this->iterator(
            $url, $handler, array($this, 'prepare'), 'denied'
        );

        return $this;
    }

    /**
     *  Append processors.
     *
     *  @var array $processors
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function processor(array $processors)
    {
        $this->processors->add($processors);

        return $this;
    }

    /**
     *  Proceed group.
     *
     *  @var array $group
     *  @var Closure $group
     *
     *  @return \Panda\Foundation\Http\RoutingDispatchAbstract
     */
    public function group(array $group, Closure $callback)
    {
        $alinement = $this->alinement;

        if (
            array_key_exists('processor', $group)
        ) {
            $processor = is_array($group['processor']) ? $group['processor'] : array($group['processor']);

            $this->alinement['processor'] = array_key_exists('processor', $this->alinement) ? 
                 array_merge($this->alinement['processor'], $processor) : $processor;
        }

        if (
            array_key_exists('namespace', $group)
        ) {
            $namespace = $group['namespace'];

            $this->alinement['namespace'] = array_key_exists('namespace', $this->alinement) ? 
                 sprintf('%s\\%s', $this->alinement['namespace'], $namespace) : $namespace;
        }

        call_user_func($callback, $this);

        $this->alinement = $alinement;

        return $this;
    }

    /**
     *  Dispatch router logic.
     *
     *  @return mixed
     */
    public function run(ClientRequestedInterface $request)
    {
        $current_url = $request->url();

        foreach($this->routes->all() as $url => $essence) {
            if (
                preg_match($this->prepare_address($url), $current_url, $matches)
            ) {
                array_shift($matches); $matches = array_filter($matches);

                $response = $this->dispatch($essence, $matches, $request);
                $floating = is_subclass_of($response, 'Panda\Foundation\Http\ControllerNativeAbstract');

                if (
                    !$floating || (
                        $floating && !$response->skipped()
                    )
                ) {
                    return $response;
                }
            }
        }

        foreach(
            array_merge(
                $this->denied->except('*'), $this->denied->only('*')
            ) as $url => $essence
        ) {
            if (
                $request->is($url)
            ) {
                return $this->dispatch($essence, array(), $request);
            }
        }
    }

    /**
     *  Dispatch route if compare it successfully.
     *
     *  @var array $essence
     *  @var array $matches
     *
     *  @return mixed
     */
    protected function dispatch($essence, array $matches = array(), ClientRequestedInterface $request)
    {
        if (
            array_key_exists('processor', $essence)
        ) {
            foreach (
                $this->processors->only($essence['processor']) as $processor
            ) {
                $instance = new $processor($request);
                $response = $instance->handle();

                if ($instance->skipped() === false) {
                    return $response; 
                }
            }
        }

        if (
            is_string($essence['handler'])
        ) {
            list($controller, $event) = explode('@', $essence['handler']);

            $controller = array_key_exists('namespace', $essence) ? 
                sprintf('%s\\%s', $essence['namespace'], $controller) : $controller;

            return call_user_func_array(array(
                new $controller($request), $event
            ), $matches);
        }
        
        return call_user_func_array($essence['handler'], $matches);
    }

    /**
     *  Iteration keypair.
     *
     *  @var array $essence
     *  @var array $matches
     *
     *  @return mixed
     */
    protected function iterator($key, $equal = null, $callback, $pay = null) {
        if (
            is_array($key) && $equal === null
        ) {
            foreach ($key as $_key => $_equal) {
                call_user_func($callback, $_key, $_equal, $pay);
            }
        } else {
            call_user_func($callback, $key, $equal, $pay);
        }

        return;
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var string $url
     *  @var mixed $handler
     *
     *  @return mixed
     */
    protected function prepare($url, $handler = null, $thread = 'routes')
    {
        if (
            is_string($url) && is_callable($handler) || (
                is_string($handler) && strpos($handler, '@') !== false
            )
        ) {
            $this->append($url, $handler, $thread);
        }
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var string $url
     *  @var mixed $handler
     *
     *  @return mixed
     */
    protected function append($url, $handler, $argument = 'routes')
    {
        $container = array('handler' => $handler);

        foreach (array('processor', 'namespace') as $thread) {
            if (
                array_key_exists($thread, $this->alinement)
            ) {
                $container[$thread] = $this->alinement[$thread];
            }
        }

        $this->{$argument}->set($url, $container);
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var string $url
     *
     *  @return mixed
     */
    protected function prepare_address($url)
    {
        $can = '[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)]+'; 

        $xor = str_replace(
            array('/', '[', ']', '*'), array('\/', '(|', ')', $can), $url
        );

        $all = preg_replace(
            '/\:[a-zA-Z0-9\_\-]+/', sprintf('(%s)', $can), $xor
        );

        return sprintf('/^%s$/', $all);
    }
}
