<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.6.0
 */

namespace Panda\NoSQL\Adapter;

use Redis as ExtRedis;
use Panda\Error\Narrator;
use Panda\NoSQL\Adapter;

/**
 *  NoSQL Redis Abstract
 *
 *  @subpackage NoSQL
 */
class Redis extends Adapter
{
    /**
     *  @const SERIALIZE_NONE
     */
    const SERIALIZE_NONE        = 'none';

    /**
     *  @const SERIALIZE_PHP
     */
    const SERIALIZE_PHP         = 'php';

    /**
     *  @const SERIALIZE_IGBINARY
     */
    const SERIALIZE_IGBINARY    = 'igbinary';

    /**
     *  @var \Redis
     */
    protected $adapter;

    /**
     *  @param array
     */
    public function __construct(array $conf = [])
    {
        $host = array_key_exists('host', $conf) ? $conf['host'] : '127.0.0.1';
        $port = array_key_exists('port', $conf) ? $conf['port'] : 6379;

        if (class_exists(ExtRedis::class) === false) {
            throw new Narrator('Redis extension doesn`t exists.');
        }

        $this->adapter = $adapter = new ExtRedis();

        if ($adapter->connect($host, $port) === false) {
            throw new Narrator('Redis server come away.');
        }

        if (array_key_exists('serialize', $conf)) {
            $this->serialize($conf['serialize']);
        } else {
            $this->serialize(static::SERIALIZE_PHP);
        }

        if (array_key_exists('prefix', $conf)) {
            $this->prefix($conf['prefix']);
        }
    }

    /**
     *  @param array $conf
     * 
     *  @return \Redis
     */
    public static function factory(array $conf = [])
    {
        return new static($conf);
    }

    /**
     *  @param  string $seriailizer
     *  
     *  @return \Panda\NoSQL\Adapter\Redis
     */
    public function serialize($seriailizer = 'none')
    {
        if ($seriailizer === static::SERIALIZE_NONE) {
            $this->adapter->setOption(ExtRedis::OPT_SERIALIZER, ExtRedis::SERIALIZER_NONE);
        } elseif ($seriailizer === static::SERIALIZE_PHP) {
            $this->adapter->setOption(ExtRedis::OPT_SERIALIZER, ExtRedis::SERIALIZER_PHP);
        } elseif ($seriailizer === static::SERIALIZE_IGBINARY) {
            $this->adapter->setOption(ExtRedis::OPT_SERIALIZER, ExtRedis::SERIALIZER_IGBINARY);
        } else {
            throw new Narrator("Invalid Redis serializer option.");
        }

        return $this;
    }

    /**
     *  @param  string $prefix
     *  
     *  @return \Panda\NoSQL\Adapter\Redis
     */
    public function prefix($prefix)
    {
        $this->adapter->setOption(ExtRedis::OPT_PREFIX, $prefix);

        return $this;
    }

    /**
     *  @param  string $prefix
     *  
     *  @return \Panda\NoSQL\Adapter\Redis
     */
    public function adapter()
    {
        return $this->adapter;
    }
}
