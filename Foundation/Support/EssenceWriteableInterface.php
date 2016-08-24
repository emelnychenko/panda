<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Writeable Container Interface
 *
 *  @subpackage Support
 */
interface EssenceWriteableInterface
{
    public function add($container);
    public function __set($key, $equal);
}