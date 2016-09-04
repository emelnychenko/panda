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
    protected $dir      = null;

    protected $packages = [];

    public function __construct($dir, $packages = [])
    {
        $this->dir = $dir;

        $packages = isset($packages) ? (
            is_array($packages) ? $packages : [$packages]
        ) : [];

        $this->packages = $packages;
    }

    public static function init($dir, $packages = [])
    {
        return new static($dir, $packages);
    }

    /**
     *  Return whole shared result.
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

// Module::sign();
