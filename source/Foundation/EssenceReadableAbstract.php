<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Readable Container Abstract
 *
 *  @subpackage Foundation
 */
abstract class EssenceReadableAbstract implements EssenceReadableInterface
{
    /**
     *  @var array
     */
    protected $container;

    /**
     *  @var array
     */
    protected static $identity = 'container';

    /**
     *  Get container value by asoociation key.
     *
     *  @var string $key
     *  @var mixed  $default
     *
     *  @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->container) ? $this->container[$key] : $default;
    }

    /**
     *  Get container values by key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return array_intersect_key(
            $this->container, array_flip($keys)
        );
    }

    /**
     *  Get container values which not in key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return array_diff_key(
            $this->container, array_flip($keys)
        );
    }

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function exists($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function has($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    /**
     *  Get whole container.
     *
     *  @return array
     */
    public function all()
    {
        return $this->container;
    }

    /**
     *  Magic override get.
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     *  Magic override has.
     *
     *  @var string $key
     *
     *  @return bool
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->container);
    }
}
