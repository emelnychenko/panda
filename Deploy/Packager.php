<?php

/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.3.0
 */
namespace Panda\Deploy;

/**
 *  Deploy Packager
 *
 *  @subpackage Essence
 */
class Packager
{
    /**
     *  @var string
     */ 
    protected $dir      = null;

    /**
     *  @var array
     */ 
    protected $packages = [];

    /**
     *  @var string $dir
     *  @var array  $packages
     */
    public function __construct($dir, $packages = [])
    {
        $this->dir = $dir;

        $packages = isset($packages) ? (
            is_array($packages) ? $packages : [$packages]
        ) : [];

        $this->packages = $packages;
    }

    /**
     *  @var string $dir
     *  @var array  $packages
     */
    public static function init($dir, $packages = [])
    {
        return new static($dir, $packages);
    }

    /**
     *  Include all packages.
     *
     *  @var \Panda\Deploy\Applique $app
     *
     *  @return array
     */
    public function unpack(Applique $app)
    {
        foreach ($this->packages as $package) {
            $bootstrap = $app->path($this->dir . $package . '/bootstrap.php');

            if (file_exists($bootstrap)) {
                include $bootstrap;
            } 
        }
    }
}
