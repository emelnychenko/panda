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
 *  Swift Page Interface
 *
 *  @subpackage Swift
 */
interface PageInterface
{
    public function layout($page);
    public function hold($key, $default = '');
    public function fill($key, $value = null);
    public function part($page);
    public function inner();
    public function render();
}
