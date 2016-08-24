<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Writeable Container Abstract
 *
 *  @subpackage Support
 */
abstract class EssenceWriteableAbstract extends EssenceReadableAbstract implements EssenceWriteableInterface
{
    public function add($container)
    {
        $this->container = $this->array_replace($this->container, $container);

        return $this;
    }

    public function __set($key, $equal)
    {
        return $this->container[$key] = $value;
    }
}

