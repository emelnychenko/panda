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
        $buffer  = $factory->adapter()->hGet(
            $factory->key($key), $table
        );
        
        $factory->table = $table;

        if ($buffer === false) {
            $factory->shared = $factory->scalar() === true ? null : [];

            return $factory;
        }

        $factory->decode($buffer);

        return $factory;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function save()
    {
        $this->adapter()->hSet(
            $this->key, $this->table, $this->encode()
        );

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
