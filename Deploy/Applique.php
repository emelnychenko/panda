<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.4.0
 */

namespace Panda\Deploy;

use Panda\Essence\Writeable     as Essence;
use Panda\Bootloader            as Bootloader;
use Panda\Secure\Guard          as Guard;
use Panda\Database\Manager      as Database;
use Panda\Http\Router           as Router;
use Panda\Http\Request          as Request;
use Panda\Swift\View            as View;
use Panda\Console\Bamboo        as Bamboo;
use Panda\NoSQL\Manager         as NoSQL;

/**
 *  Applique Deployment Layer
 *
 *  @subpackage Deploy
 */
class Applique
{
    /**
     *  @var string
     */
    protected $chroot;

    /**
     *  @var array
     */
    protected $config;

    /**
     *  @var array
     */
    protected $invoke;

    /**
     *  @var array
     */
    protected $hoster;

    /**
     *  @var string $chroot
     *  @var array  $config
     */
    public function __construct($chroot, $config = null)
    {
        chdir($chroot .= '/');

        $this->chroot = $chroot;
        $this->config = Essence::factory();
        $this->invoke = Essence::factory();
        $this->hoster = Essence::factory();
        $request      = Request::factory(); 
        
        $this->register([
            'loader' => Bootloader::class,
            'request'=> $request,
            'router' => Router::factory($request),
            'bamboo' => Bamboo::class,
            'view'   =>   View::class
        ]);

        if ($config !== null) {
            $this->config($this->path($config), 'push');
        }

           Guard::register($this->config->get('defender', null));
           NoSQL::factory($this->config->get('nosql', []));
        Database::factory($this->config->get('database', []));
    }

    /**
     *  @var string $chroot
     *  @var string $config
     *
     *  @return mixed
     */
    public static function init($chroot, $config = null)
    {
        return new static($chroot, $config);
    }

    /**
     *  @var string $key
     *  @var string $action
     *
     *  @return mixed
     */
    public function config($key = null, $action = 'pull')
    {
        if ($action === 'push') {
            if (is_array($key) === null) {
                $this->config->replace($key);
            } else {
                foreach (glob($key) as $path) {
                    $this->config->replace(include $path);
                }
            }

            return $this;
        }


        if ($key === null) {
            return $this->config;
        }

        return $this->config->get($key, null);
    }

    /**
     *  @var string $path
     *
     *  @return string
     */
    public function path($path = null)
    {
        return $this->chroot . $path;
    }

    /**
     *  @var string $invoke
     *  @var mixed  $service
     *
     *  @return \Panda\Deploy\Applique
     */
    public function register($invoke, $service = null)
    {
        $invokable = is_array($invoke) ? $invoke : [$invoke => $service];

        foreach ($invokable as $invoke => $service) {
            if (is_object($service)) {
                $this->hoster->set(get_class($service), $service);
                $this->invoke->set($invoke, get_class($service));
            } else {
                $this->invoke->set($invoke, $service);
            }
        }

        return $this;
    }

    /**
     *  @var string $invoke
     *
     *  @return mixed
     */
    public function invoke($invoke, $default = null)
    {
        $hosted = $this->invoke->get($invoke, null);

        if ($hosted === null) {
            return null;
        }

        $service = $this->hoster->get($hosted, $default);

        if ($service === null) {
            $service = new $hosted;

            $this->hoster->set($hosted, $service);
        }

        return $service;
    }

    /**
     *  @var string $invoke
     *
     *  @return mixed
     */
    public function load($namespace, $dir = null)
    {
        $this->invoke('loader')->load($namespace, $dir);

        return $this;
    }

    /**
     *  @var string $method
     *  @var array  $argument
     *
     *  @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->invoke($key, $default);
    }

    /**
     *  @var string $method
     *
     *  @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     *  @var string $method
     *  @var array  $argument
     *
     *  @return mixed
     */
    public function __call($method, $argument)
    {
        return $this->invoke($method);
    }

    /**
     *  @var \Panda\Deploy\Applique $app
     *
     *  @return string
     */
    public static function send(Applique $app)
    {
        echo php_sapi_name() === 'cli' ? $app->bamboo()->run($app) : $app->router()->run($app);
    }
}
