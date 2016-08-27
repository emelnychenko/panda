<?php

namespace Panda;

use Panda;
use Panda\Support;
use Panda\Http;

class Bootstrap
{
    protected function __construct($appdir, $config)
    {
        $this
            ->chroot($appdir)
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
            'ladder'        => new Ladder,
            'request'       => Http\Request::singleton(), 
            'router'        => Http\Router::singleton(), 
            'config'        => $config
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

    protected function chroot($directory)
    {
        chdir($directory); return $this;
    }

    public static function make($appdir, $config)
    {
        return new static($appdir, $config);
    }
}
