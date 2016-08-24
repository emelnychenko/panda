<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
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
    /**
     *  Set container array data.
     *
     *  @var array $container
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function add(array $container = null)
    {
        $this->container = array_replace($this->container, $container);

        return $this;
    }

    /**
     *  Set container by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function set($key, $equal)
    {
        $this->container[$key] = $equal;

        return $this;
    }

    /**
     *  Set magic container by key.
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
