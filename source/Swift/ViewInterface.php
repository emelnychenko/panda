<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Swift;

/**
 *  Swift View Interface
 *
 *  @subpackage Swift
 */
interface ViewInterface
{
    public function register($dir, $extension = null);

    public function compile($file, array $container = array(), $prevent = false);

    public function finder($file);
}
