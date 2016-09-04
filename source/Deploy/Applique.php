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
use Panda\Essence\Defender      as Defender;

/**
 *  Applique Deployment Layer
 *
 *  @subpackage Deploy
 */
class Applique
{
    protected $chroot;

    protected $config;

    protected $invoke;

    protected $hoster;

    public function __construct($chroot, $config = null)
    {
        $this->chroot = $chroot;
        $this->config = Essence::factory();
        $this->invoke = Essence::factory();
        $this->hoster = Essence::factory();
        
        $this->register([
            'loader' => Bootloader::class
        ]);

        if ($config !== null) {
            $this->config($this->path($config), 'push');
        }

        Defender::lock($this->config->get('secret', null));
    }

    public function config($key = null, $action = 'pull')
    {
        if ($action === 'pull') {
            if ($key === null) {
                return $this->config;
            }       

            return $this->config->get($key, null);
        }

        if ($action === 'push') {
            foreach (glob($key) as $path) {
                $this->config->replace(include $path);
            }
        }
    }

    public static function init($chroot, $config = null)
    {
        return new static($chroot, $config);
    }

    public function path($path)
    {
        return $this->chroot . $path;
    }

    /**
     *  Return whole shared result.
     *
     *  @return array
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
     *  Return whole shared result.
     *
     *  @return array
     */
    public function invoke($invoke)
    {
        $hosted = $this->invoke->get($invoke, null);

        if ($hosted === null) {
            return null;
        }

        $service = $this->hoster->get($hosted);

        if ($service === null) {
            $service = new $hosted;

            $this->hoster->set($hosted, $service);
        }

        return $service;
    }

    public function load($namespace, $dir = null)
    {
        $this->invoke('loader')->load($namespace, $dir);

        return $this;
    }

    public function get($key, $default)
    {
        return $this->invoke($key);
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __call($method, $argument)
    {
        return $this->invoke($method);
    }

    public static function send(Applique $app)
    {
        echo php_sapi_name() === 'cli' ? $app->bamboo()->run($app) : $app->router()->run($app);
    }
}

