<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;
  
use Panda\Foundation\DatabaseAdapterAbstract;
use Panda\Foundation\DatabaseMySQLAdapter;
use Panda\Foundation\DatabaseSQLiteAdapter;

/**
 *  Panda Bootstrap
 *
 *  @subpackage Framework
 */
class Database implements DatabaseInterface 
{
    const MYSQL     = 'mysql';

    const SQLITE    = 'sqlite';

    /**
     *  @var array
     */
    protected $connections = array();
    /**
     *  Append array composite of configuration.
     * 
     *  @var array $conf_tree
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function create(array $configs)
    {
        $singleton = static::singleton();

        foreach($configs as $connection => $config) {
            $singleton->append($connection, $config);
        }

        return $singleton;
    }
    /**
     *  Append configuraiton with association key.
     * 
     *  @var array $connection
     *  @var array $connect_arr
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public function append($connection = 'default', $floating)
    {
        if (
            is_array($floating) && array_key_exists('adapter', $floating)
        ) {
            if (
                $floating['adapter'] === static::MYSQL 
            ) {
                $this->connections[$connection] = DatabaseMySQLAdapter::factory($floating, true);
            } elseif (
                $floating['adapter'] === static::SQLITE 
            ) {
                $this->connections[$connection] = DatabaseSQLiteAdapter::factory($floating, true);
            }
        } elseif (
            is_object($floating) && is_subclass_of(DatabaseAdapterAbstract::class) 
        ) {
            $this->connections[$connection] = $floating;
        }

        return $this->connections[$connection];
    }
    /**
     *  Append MySQL configuraiton with association key.
     * 
     *  @var array $connection
     *  @var array $connect_arr
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function mysql($connection = 'default', array $config = null)
    {
        return static::singleton()->append(
            $connection, array_replace($config, array('adapter' => static::ADAPTER_MYSQL))
        );
    }
    /**
     *  Append SQLite configuraiton with association key.
     * 
     *  @var array $connection
     *  @var array $connect_arr
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function sqlite($connection = 'default', array $config = null)
    {
        return static::singleton()->append(
            $connection, array_replace($config, array('adapter' => static::ADAPTER_SQLITE))
        );
    }

    /**
     *  Pull AdapterProvider instance.
     * 
     *  @var array $connection
     *
     *  @return \Blink\Database\AdapterProviderAbstract
     */
    public static function get($connection = 'default')
    {
        $singleton = static::singleton();

        return array_key_exists($connection, $singleton->connections) ?
            $singleton->connections[$connection] : null;
    }

    /**
     *  Singleton call.
     * 
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function singleton()
    {
        static $instance = null;
        
        if (
            $instance === null
        ) {
            $instance = new static();
        }
        return $instance;
    }
}
