<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;
  

/**
 *  Panda Bootstrap
 *
 *  @subpackage Framework
 */
class Bootstrap
{
    /**
     *  @var mixed $config
     */ 
    protected function __construct($config)
    {
        $this
            ->config($config)
            ->putapp($config)
        ;
    }
 
    /**
     *  @var mixed $config
     *
     *  @return Panda\Bootstrap
     */ 
    protected function config(&$config)
    {
        $config = is_array($config) ? $config : include($config);

        if (
            array_key_exists('config', $config)
        ) {
            foreach(glob($config['config']) as $partial) {
                $config = array_replace(
                    include($partial), $config 
                );
            }
        }

        return $this;
    }

    /**
     *  @var mixed $config
     */ 
    protected function putapp($config)
    {
        $support = Support::singleton()->append(array(
            'database'  => Database::create(
                array_key_exists('database', $config) ? $config['database'] : array()
            ),
            'request'   => Request::singleton(), 
            'ladder'    =>  Ladder::singleton(),
            'router'    =>  Router::singleton(),
            'swift'     =>   Swift::singleton(), 
            'config'    =>  $config
        ));

        $this->package($support, $config);

        echo $this->execute($support);
    }

    /**
     *  @var Panda\SupportInterface $support
     *  @var mixed $config
     *
     *  @return Panda\Bootstrap
     */ 
    protected function package(SupportInterface $support, $config)
    {
        if (
            array_key_exists('plugin_dir', $config)
        ) {
            foreach (glob($config['plugin_dir'] . '/*/Bootstrap.php') as $bootstrap) {
                include $bootstrap;
            }
        }

        if (
            array_key_exists('plugin', $config)
        ) {
            foreach ($config['plugin'] as $plugin) {
                $boostrap = sprintf('%s\\Bootstrap', $plugin);

                new $boostrap($support);
            }
        }

        return $this;
    }

    /**
     *  @var Panda\SupportInterface $support
     */ 
    protected function execute($support)
    {
        return $support->get('router')->run($support);
    }

    /**
     *  @var mixed $config
     *
     *  @return Panda\Bootstrap
     */ 
    public static function make($config)
    {
        return new static($config);
    }
}
