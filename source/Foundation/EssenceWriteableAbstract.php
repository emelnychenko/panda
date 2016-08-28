<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Writeable shared Abstract
 *
 *  @subpackage Foundation
 */
abstract class EssenceWriteableAbstract extends EssenceReadableAbstract implements EssenceWriteableInterface
{
    /**
     *  Set shared array data.
     *
     *  @var array $shared
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function add(array $shared = null)
    {
        $this->shared = array_replace($this->shared, $shared);

        return $this;
    }

    /**
     *  Set shared by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function set($key, $equal)
    {
        $this->shared[$key] = $equal;

        return $this;
    }

    /**
     *  Set magic shared by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function __set($key, $equal)
    {
        return $this->set($key, $equal);
    }
}
