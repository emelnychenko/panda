<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Readable Container Interface
 *
 *  @subpackage Support
 */
interface EssenceReadableInterface
{
    public function only($keys);
    public function except($keys);
    public function exists($keys);
    public function has($keys);

    public function __isset($key);
    public function __get($key);
}