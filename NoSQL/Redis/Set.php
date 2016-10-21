<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.6.0
 */

namespace Panda\NoSQL\Redis;

/**
 *  NoSQL Redis Abstract
 *
 *  @subpackage NoSQL
 */
abstract class Set extends \Panda\NoSQL\Redis
{
    public static function find($key = null)
    {
        return static::all($key);
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function all($key = null)
    {
        $adapter = ($factory = static::factory())->adapter();

        $buffer =  $adapter->sMembers($factory->key($key));

        if (empty($buffer) === true) {
            $factory->shared = $factory->scalar() === true ? null : [];

            return $factory;
        }

        $factory->decode($buffer);

        return $factory;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function size($key = null)
    {
        $adapter = ($factory = static::factory())->adapter();

        return $adapter->sCard($factory->key($key));
    }

    public function add($val)
    {
        $inset = array_search($val, $this->shared, true);

        if ($inset === false) {
            array_push($this->shared, $val);

            $this->adapter()->sAdd($this->key, $val);
        }

        return $this;
    }

    // public function push($val)
    // {
    //     return $this->add($val);
    // }

    public function join($pattern = ', ')
    {
        return implode($pattern, $this->shared);
    }

    public function iterate(callable $callback)
    {
        foreach ($this->shared as $val) {
            call_user_func($callback, $val);
        }
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function save()
    {
        // silent

        return $this;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function remove($vals = null)
    {
        if ($vals === null) return $this;

        $adapter = $this->adapter();

        foreach (is_array($vals) ? $vals : [$vals] as $val) {
            $adapter->sRem($this->key, $val);
        }

        return $this;
    }
}
