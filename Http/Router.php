<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Http;

use Panda\Essence\Writeable             as Essence;
use Panda\Alloy\FactoryInterface        as Factory;
use Panda\Http\ControllerAbstract       as Controller;
use Panda\Http\Controller\GuardAbstract as Guard;
use Panda\Deploy\Applique               as Applique;

/**
 *  Http Router
 *
 *  @subpackage Http
 */
class Router implements RouterInterface, Factory
{
    /**
     *  @var string
     */ 
    protected $separator    = '::';

    /**
     *  @var string
     */ 
    protected $capture      = '[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)]+';

    /**
     *  @var \Panda\Essence\Writeable
     */ 
    protected $route;

    /**
     *  @var \Panda\Essence\Writeable
     */ 
    protected $error;

    /**
     *  @var \Panda\Essence\Writeable
     */ 
    protected $guard;

    /**
     *  @var array
     */ 
    protected $expansion = [];

    /**
     *  @var \Panda\Http\RequestInterface
     */ 
    protected $request;

    /**
     *  Get headers Essence, value or set header.
     *
     *  @return mixed
     */
    public function __construct(RequestInterface $request = null)
    {
        $this->route    = Essence::factory();
        $this->guard    = Essence::factory();
        $this->error    = Essence::factory();
        $this->request  = isset($request) ? $request : Request::factory();
    }

    /**
     *  Get headers Essence, value or set header.
     *
     *  @return mixed
     */
    public static function factory(RequestInterface $request = null)
    {
        return new static($request);
    }

    /**
     *  Register route with pattern rules.
     *
     *  @var mixed $pattern
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function add($pattern, $url, $handler = null)
    {
        $matches = is_array($pattern) ? $pattern : explode('|', $pattern);

        if (in_array($this->request->method(), $matches, true)) {
            $this->append(
                is_array($url) ? $url : [$url => $handler], 'route'
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
     *  @return \Panda\Http\Router
     */
    public function any($url, $handler = null)
    {
        return $this->add(['GET', 'POST', 'PUT', 'DELETE'], $url, $handler);
    }

    /**
     *  Register route for GET method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function get($url, $handler = null)
    {
        return $this->add(['GET'], $url, $handler);
    }

    /**
     *  Register route for POST method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function post($url, $handler = null)
    {
        return $this->add(['POST'], $url, $handler);
    }

    /**
     *  Register route for PUT method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function put($url, $handler = null)
    {
        return $this->add(['PUT'], $url, $handler);
    }

    /**
     *  Register route for DELETE method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function delete($url, $handler = null)
    {
        return $this->add(['DELETE'], $url, $handler);
    }

    /**
     *  Register route for DENIED method.
     *
     *  @var mixed $url
     *  @var mixed $handler
     *
     *  @return \Panda\Http\Router
     */
    public function deny($url = '*', $handler = null)
    {
        $this->append(
            is_array($url) ? $url : [$url => $handler], 'error'
        );

        return $this;
    }

    /**
     *  Append processors.
     *
     *  @var array $guard
     *
     *  @return \Panda\Http\Router
     */
    public function guard(array $guard)
    {
        $this->guard->replace($guard);

        return $this;
    }

    public function request()
    {
        return $this->request;
    }

    /**
     *  Proceed group.
     *
     *  @var array $group
     *  @var Closure $group
     *
     *  @return \Panda\Http\Router
     */
    public function group(array $group, callable $callback)
    {
        $expansion = $this->expansion;

        $this->expansion($group, function($key, $equal) use ($group, $expansion) {
            $expansion = array_key_exists($key, $expansion) ? $expansion[$key] : null;

            if ($key === 'namespace') {
                return $this->expansion[$key] = implode('\\', array_merge(
                    isset($expansion) ? [$expansion] : [], is_string($equal) ? [$equal] : []
                ));
            }

            return $this->expansion[$key] = array_merge(
                is_array($expansion) ? $expansion : [], is_array($equal) ? $equal : [$equal]
            );
        });

        call_user_func($callback, $this);

        $this->expansion = $expansion;

        return $this;
    }

    /**
     *  Dispatch router logic.
     *
     *  @return mixed
     */
    public function run(Applique $applique = null)
    {
        $url = $this->request()->url();
        $all = array_merge(
            $this->route->all(), $this->error->except('*'), $this->error->only('*')
        );

        foreach($all as $identifier => $essence) {
            if (preg_match($this->capture($essence['url']), $url, $matches)) {
                array_shift($matches); $matches = array_filter($matches);

                $response = $this->dispatch($essence, $matches, $applique);
                $floated  = is_subclass_of($response, 'Panda\Http\ControllerAbstract');

                if ($floated === false || ($floated && !$response->passed())) {
                    return $response;
                }
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
    protected function dispatch($essence, array $matches = [], Applique $applique = null)
    {
        if (array_key_exists('guard', $essence)) {
            foreach ($this->guard->only($essence['guard']) as $guard) {
                $instance = $guard::factory($applique);
                $response = $instance->inspect();

                if ($instance->passed() === false) {
                    return $response; 
                }
            }
        }

        if (is_string($essence['handler'])) {
            list($controller, $event) = explode($this->separator, $essence['handler']);

            $controller = array_key_exists('namespace', $essence) ? 
                sprintf('%s\\%s', $essence['namespace'], $controller) : $controller;

            return call_user_func_array([$controller::factory($applique), $event], $matches);
        }
        
        return call_user_func_array($essence['handler'], $matches);
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var string $url
     *  @var mixed $handler
     *
     *  @return mixed
     */
    protected function append($arrayable, $essence = 'route')
    {
        foreach ($arrayable as $url => $handler) {
            $shared = ['url' => $url, 'handler' => $handler];

            $this->expansion($this->expansion, function($key, $equal) use (&$shared) {
                $shared[$key] = $equal;
            });

            if ($essence === 'error') {
                $this->{$essence}->set($url, $shared);
            } else {
                $this->{$essence}->set(uniqid(), $shared);
            }
        }
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var array $shared
     *  @var mixed $handler
     *
     *  @return mixed
     */
    protected function expansion($shared, callable $callback)
    {
        foreach (['guard', 'namespace'] as $key) {
            if (array_key_exists($key, $shared)) {
                call_user_func($callback, $key, $shared[$key]);
            }
        }
    }

    /**
     *  Dispatch route if compare is successfully.
     *
     *  @var string $url
     *
     *  @return mixed
     */
    protected function capture($url)
    {
        $xor = str_replace(
            ['/', '[', ']', '*'], ['\/', '(|', ')', '(.*?)'], $url
        );

        $all = preg_replace(
            '/\:[a-zA-Z0-9\_\-]+/', sprintf('(%s)', $this->capture), $xor
        );

        return sprintf('/^%s$/', $all);
    }
}
