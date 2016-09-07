<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Swift;

use Panda\Essense\WriteableAbstract     as Essense;

/**
 *  Swift View
 *
 *  @subpackage Swift
 */
class View implements ViewInterface
{
    /**
     *  @var array
     */ 
    protected $pools = array();

    public function register($dir, $extension = null)
    {
        $arrayable = is_array($dir) ? $dir : [$dir => $extension];

        foreach ($arrayable as $dir => $extension) {
            $this->pools[$dir] = $extension;
        }
    }

    public function compile($file, array $container = array(), $prevent = false)
    {
        return new Page($this, $file, $container, $prevent);   
    }

    public function finder($file)
    {
        foreach ($this->pools as $dir => $extension) {
            $filepath = sprintf('%s/%s.%s', $dir, $file, $extension);

            if (file_exists($filepath)) {
                return $filepath;
            }
        }

        return null;
    }
}
