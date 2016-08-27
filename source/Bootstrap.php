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
    protected function __construct($config)
    {
        $this
            ->config($config)
            ->putapp($config)
        ;
    }

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

    protected function putapp($config)
    {
        $support = Support::singleton()->append(array(
            'request'       => Request::singleton(), 
            'ladder'        =>  Ladder::singleton(),
            'router'        =>  Router::singleton(), 
            'config'        =>  $config
        ));

        $this->package($support, $config);

        echo $this->execute($support);
    }

    protected function package(Support $support, $config)
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
    }

    protected function execute($support)
    {
        return $support->get('router')->run(
            $support->get('request')
        );
    }

    public static function make($config)
    {
        return new static($config);
    }
}
