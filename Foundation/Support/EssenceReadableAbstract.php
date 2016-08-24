<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Support;

/**
 *  Readable Container Abstract
 *
 *  @subpackage Support
 */
abstract class EssenceReadableAbstract implements EssenceReadableInterface
{
    protected $container;

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->container) ? $this->container[$key] : $default;
    }

    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return array_intersect_key(
            $this->container, array_flip($keys)
        );
    }

    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return array_diff_key(
            $this->container, array_flip($keys)
        );
    }

    public function exists($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    public function has($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    public function all()
    {
        return $this->container;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __isset($key)
    {
        return array_key_exists($key, $this->container);
    }
}

