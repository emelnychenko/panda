<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.6.0
 */

namespace Panda\NoSQL;

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
    protected static $adapter   = 'redis.default';

    /**
     *  @var string
     */
    protected $name             = 'panda.redis';

    /**
     *  @var string
     */
    protected $key              = null;

    /**
     *  @var numeric
     */
    protected $ttl              = 3600;

    /**
     *  @var string (json|php)
     */
    protected $serialization    = 'json';

    /**
     *  @var string 
     */
    protected $input            = null;

    /**
     *  @var string 
     */
    protected $separator        = '::';

    /**
     *  Construct instance.
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function __construct()
    {
        # implements ...
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function factory() {
        return new static();
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function find($key = null, $arrayable = false)
    {
        return static::factory()->__find($key, $arrayable);
    }

    /**
     *  Find method implementation.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function __find($key = null, $arrayable = false)
    {
        return $this->enclosed($key, function($adapter, $chain) use ($key, $arrayable) {
            return $this->compile_one($key, $adapter->get($chain), $arrayable);
        });
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function by($pattern = null, $arrayable = false)
    {
        return static::factory()->__by($pattern, $arrayable);
    }

    /**
     *  Find method implementation.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function __by($pattern = null, $arrayable = false)
    {
        return $this->enclosed($pattern, function($adapter, $chain) use ($arrayable) {
            return $this->compile_all($adapter, $chain, $arrayable);
        });
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public static function all($arrayable = false)
    {
        return static::factory()->__all($arrayable);
    }

    /**
     *  Factory instance.
     *
     *  @var mixed $name
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function __all($arrayable)
    {
        return $this->enclosed('*', function($adapter, $chain) use ($arrayable) {
            return $this->compile_all($adapter, $chain, $arrayable);
        });
    }

    public static function create($key, array $shared = null)
    {
        $factory = static::factory();

        if (is_array($key) && $shared === null) {
            $shared = $key; $key = null;
        }
        
        $factory->key = $key;

        return $factory->set($shared)->save();
    }

    public function touch()
    {
        $this->enclosed($this->key, function($adapter, $chain) {
            $adapter->set($chain, $this->input, $this->ttl);
        });

        return $this;
    }

    /**
     *  Short call update method.
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function update($shared, $equal = null)
    {
        $this->set($shared, $equal)->save();
    }

    /**
     *  Saving data handler.
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function save()
    {
        $this->input = json_encode($this->shared);

        return $this->touch();
    }

    /**
     *  Remove call (flush in service).
     *
     *  @return \Panda\NoSQL\RedisAbstract
     */
    public function remove($key = null)
    {
        $this->enclosed($key, function($adapter, $key) {
            $adapter->delete($key);
        });

        return $this;
    }

    /**
     *  Open close enclosed execution.
     *
     *  @return mixed
     */
    protected function enclosed($key = null, callable $callback)
    {
        $adapter    = static::adapter();
        $key        = isset($key) ? sprintf('%s%s%s', $this->name, $this->separator, $key) : $this->name;

        if ($adapter !== null) {
            return call_user_func($callback, $adapter, $key);
        }

        return null;
    }

    protected function compile_one($key, $input, $arrayable = false)
    {
        $this->key   = $key;
        $this->input = $input;

        $decoded = json_decode($input, true);

        if ($arrayable) {
            return $decoded;
        }

        $this->shared = $decoded;

        return $this;
    }

    protected function compile_all($adapter, $chain, $arrayable = false)
    {
        $keychain   = $adapter->keys($chain);
        $objects    = [];

        if (empty($keychain)) return null;

        foreach ($keychain as $key) {
            $obtain     = $this->obtain($key);
            $compiled   = $this->compile_one($obtain, $adapter->get($key), $arrayable);

            $objects[$obtain] = $arrayable ? $compiled : clone $compiled;
        }

        return $objects;
    }

    protected function obtain($key)
    {
        return str_replace(
            sprintf("%s%s", $this->name, $this->separator), '', $key
        );
    }

    /**
     *  Method for return Redis adapter.
     *  
     *  @return Adapter
     */
    public static function adapter()
    {
        $host = isset($configuration['host']) ? $configuration['host'] : '127.0.0.1';
        $port = isset($configuration['port']) ? $configuration['port'] : 6379;

        // if (
        //     $this->redis_class_exist
        // ) {
        $redis = new \Redis();

        $redis->connect($host, $port);

        return $redis;
    }
}
