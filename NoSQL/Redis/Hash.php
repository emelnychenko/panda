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
abstract class Hash extends \Panda\NoSQL\Redis
{
    /**
     *  @var string
     */
    protected $table        = null;

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function find($table = 'panda.table', $key = null)
    {
        $adapter = ($factory = static::factory())->adapter();

        $factory->originate(
            $factory->adapter()->hGet(
                $factory->key($key), $table
            )
        );

        $factory->table = $table;

        return $factory;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function save()
    {
        $this->adapter()->hSet($this->key, $this->table, $this->shared);

        $this->originate($this->shared);

        return $this;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function remove()
    {
        $this->adapter()->hDel($this->key);

        return $this;
    }
}
