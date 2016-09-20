<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.6.0
 */

namespace Panda\NoSQL;

use Panda\Error\Narrator            as Narrator;
use Panda\Alloy\FactoryInterface    as Factory;
use Panda\Alloy\StorageInterface    as Storage;
use Panda\Essence\WriteableAbstract as Essence;

/**
 *  NoSQL Redis Abstract
 *
 *  @subpackage NoSQL
 */
abstract class RedisAbstract extends Essence implements Factory, Storage
{
    /**
     *  @var string
     */
    protected $adapter      = 'default';

    /**
     *  @var string
     */
    protected $prefix       = 'panda.redis';

    /**
     *  @var string
     */
    protected $key          = 'panda.cell';

    /**
     *  @var string
     */
    protected $separator    = '::';

    /**
     *  @var numeric
     */
    protected $ttl          = 3600;

    /**
     *  @var array
     */
    protected $shared       = [];

    /**
     *  @var array
     */
    protected $origin       = [];

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function factory()
    {
        return new static();
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function find($key = null)
    {
        $adapter = ($factory = static::factory())->adapter();

        $factory->originate(
            $adapter->get(
                $factory->key($key)
            )
        );

        return $factory;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function update($shared, $equal = null)
    {
        return $this->set($shared, $equal)->save();
    }

    public function touch()
    {
        $this->adapter()->expireAt($this->key, time() + $this->ttl); 
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function save()
    {
        $this->adapter()->set($this->key, $this->shared, time() + $this->ttl);

        $this->originate($this->shared);

        return $this;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function remove()
    {
        $this->adapter()->delete($this->key);

        return $this;
    }

    /**
     *  Exchange basic key
     * 
     *  @param  string $key
     * 
     *  @return string
     */
    public function key($key = null)
    {
        return $this->key = $key === null ? $this->key : sprintf('%s::%s', $this->key, $key);
    }

    /**
     *  @param  mixied $shared
     * 
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function originate($shared = null)
    {
        if (is_array($shared) === true) {
            $this->shared = $shared;
        }

        $this->origin = $this->shared;

        return $this;
    }

    /**
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function adapter()
    {
        static $adapter = null;

        if ($adapter === null) {
            $adapter = Manager::get($this->adapter);

            if ($adapter === null) throw new Narrator("Redis adapter doesn`t exist.");

            $adapter->prefix($this->prefix . $this->separator);
        }

        return $adapter->adapter();
    }
}
