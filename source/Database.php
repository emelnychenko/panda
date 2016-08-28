<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;
  
use Panda\Foundation\EssenceWriteableAbstract;

/**
 *  Panda Bootstrap
 *
 *  @subpackage Framework
 */
class Database extends EssenceWriteableAbstract implements DatabaseInterface 
{
    /**
     *  @var array
     */
    protected $connection = array();
    /**
     *  Append array composite of configuration.
     * 
     *  @var array $conf_tree
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function create(array $conf_tree)
    {
        $instance = static::singleton();
        foreach ($conf_tree as $connection => $conf_item) {
            $instance->add($connection, $conf_item);
        }
        return $instance;
    }
    /**
     *  Append configuraiton with association key.
     * 
     *  @var array $connection
     *  @var array $connect_arr
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function add($connection = 'default', array $connect_arr = null, &$prototype = null)
    {
        if (
            array_key_exists('adapter', $connect_arr) && AdapterProviderFactory::verify($connect_arr['adapter'])
        ) {
            return static::singleton()->__push(
                $connection, AdapterProviderFactory::create(
                    $connect_arr['adapter'], $connect_arr, $prototype, true
                )
            );
        }
    }
    /**
     *  Append MySQL configuraiton with association key.
     * 
     *  @var array $connection
     *  @var array $connect_arr
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    public static function mysql($connection = 'default', array $connect_arr = null, &$prototype = null)
    {
        return static::singleton()->__push(
            $connection, AdapterProviderFactory::mysql($connect_arr, $prototype, true)
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
    public static function sqlite($connection = 'default', array $connect_arr = null, &$prototype = null)
    {
        return static::singleton()->__push(
            $connection, AdapterProviderFactory::sqlite($connect_arr, $prototype, true)
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
        return static::singleton()->__pull($connection);
    }
    /**
     *  Helper setter method connection.
     * 
     *  @var string $thread
     *  @var AdapterProviderInterface $adapter
     *
     *  @return \Blink\Database\ActiveRecordAdapter
     */
    protected function __push($thread = 'default', AdapterProviderInterface $adapter)
    {
        $this->adapter[$thread] = $adapter;
        return $this;
    }
    /**
     *  Helper getter method.
     * 
     *  @var string $thread
     *
     *  @return \Blink\Database\AdapterProviderAbstract
     */
    protected function __pull($thread = 'default')
    {
        return array_key_exists($thread, $this->adapter) ? $this->adapter[$thread] : null;
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
    protected function __construct()  {}
    protected function __wakeup()     {}
    protected function __clone()      {}
}
