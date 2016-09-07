<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;

/**
 *  Panda Ladder
 *
 *  @subpackage Framework
 */
interface BootloaderInterface
{
    /**
     *  Register autoloader matches.
     * 
     *  @var mixed  $namespace
     *  @var mixed  $path
     *  
     *  @return \Panda\Bootloader
     */
    public function load($namespace, $dir = null);
}
