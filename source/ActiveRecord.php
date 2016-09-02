<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda;
  
use Panda\Foundation\ActiveRecordScaleableTrait;
use Panda\Foundation\EssenceWriteableAbstract;

/**
 *  Panda Bootstrap
 *
 *  @subpackage Framework
 */
abstract class ActiveRecord extends EssenceWriteableAbstract implements ActiveRecordInterface 
{
    /**
     *  @var string
     */ 
    protected static $adapter   = 'default';

    /**
     *  @var string
     */ 
    protected $table            = null;

    /**
     *  String - one primary. Array - pair oriented.
     *
     *  @var mixed
     */ 
    protected $primary          = 'id';

    /**
     *  Basic scale implementation of one autoincrements.
     *
     *  @var mixed
     */ 
    protected $increment        = 'id';

    /**
     *  Watch primary key on insertion. And selection.
     *
     *  @var bool
     */ 
    protected $watch        = true;

    /**
     *  Scalable association columns.
     *
     *  @var string
     */ 
    protected $columns      = array();

    /**
     *  Use auto datetime touches.
     *
     *  @var bool
     */ 
    protected $timestamp    = false;

    /**
     *  Preconfigured DATETIME, TIMESTAMP format
     *
     *  @var string
     */ 
    protected $datetime     = 'Y-m-d H:i:s';

    /**
     *  Preconfigured DATE format
     *
     *  @var string
     */ 
    protected $date         = 'Y-m-d';

    /**
     *  Preconfigured TIME format
     *
     *  @var string
     */ 
    protected $time         = 'H:i:s';

    /**
     *  Preconfigured auto created_at column
     *
     *  @var string
     */ 
    protected $created_at   = 'created_at';

    /**
     *  Preconfigured auto updated_at column
     *
     *  @var string
     */ 
    protected $updated_at   = 'updated_at';

    /**
     *  Logic container for free access
     *
     *  @var array
     */ 
    protected $shared       = array();

    /**
     *  Locked container for unique selection
     *
     *  @var array
     */ 
    protected $origin       = array();

    /**
     *  Locked container for unique selection
     *
     *  @var array
     */ 
    protected $intable      = false;

    public function __construct()
    {
        !empty($this->columns) ? $this->scale_fill() : null; 
    }


    public static function factory()
    {
        return new static();
    }

    public static function create(array $shared)
    {
        return static::factory()->add($shared)->save();
    }

    public static function find($primary)
    {
        return static::factory()->__find($primary);
    }

    protected function __find($primary)
    {
        $this->intable  = true;
        $primaries      = $this->scale_selection(
            is_array($primary) ? $primary : array($this->primary => $primary)
        );

        $result = static::query(function($query) use ($primaries) {
            $query->select(
                $this->columns
            )->from(
                $this->table
            )->where(
                $primaries
            )->limit(1);
        })->one();

        return $this->compile_one($result);
    }

    public static function one(array $condition = array(), array $order = array(), $offset = null)
    {
        return static::factory()->__one($condition, $order, $offset);
    }

    protected function __one(array $condition = array(), array $order = array(), $offset = null)
    {
        $this->intable  = true;

        $result = static::query(function($query) use ($condition, $order, $offset) {
            $query->select(
                $this->columns
            )->from(
                $this->table
            )->where(
                $this->scale_selection($condition)
            )->order(
                $this->scale_selection($order)
            )->limit(
                1
            )->offset($offset);
        })->one();

        return $this->compile_one($result);
    }

    public static function by(array $condition = array(), array $order = array(), $offset = null, $limit = null)
    {
        return static::factory()->__by($condition, $order, $offset, $limit);
    }

    protected function __by(array $condition = array(), array $order = array(), $offset = null, $limit = null)
    {
        $this->intable  = true;

        $result = static::query(function($query) use ($condition, $order, $offset, $limit) {
            $query->select(
                $this->columns
            )->from(
                $this->table
            )->where(
                $this->scale_selection($condition)
            )->order(
                $this->scale_selection($order)
            )->limit(
                $limit
            )->offset(
                $offset
            );
        })->all();

        return $this->compile_all($result);
    }

    public static function all($array = false)
    {
        return static::factory()->__all();
    }

    protected function __all()
    {
        $this->intable = true;

        $result = static::query(function($query){
            $query->select($this->columns)->from($this->table);
        })->all();

        return $this->compile_all($result);
    }

    public function touch()
    {
        if ($this->intable && $this->timestamp !== false) {
            $diffed  = array(
                $this->scale_column($this->updated_at) => $this->scale_timestamp()
            );

            $primaries = $this->scale_primary($diffed);

            static::query(function($query) use ($primaries, $diffed) {
                $query->update(
                    $this->table
                )->set(
                    $diffed
                )->where(
                    $primaries
                );
            });
        }

        return $this;
    }

    public function update(array $shared)
    {
        return $this->add($shared)->save();
    }

    public function save()
    {
        $diffed     = $this->scale_pull();
        $primaries  = $this->scale_primary($diffed);

        if (
            !empty($diffed)
        ) {
            if ($this->intable) {
                if ($this->timestamp !== false) { # updated_at
                    $diffed[$this->scale_column($this->updated_at)] = $this->scale_timestamp(); 
                }

                static::query(function($query) use ($primaries, $diffed) {
                    $query->update(
                        $this->table
                    )->set(
                        $diffed
                    )->where(
                        $primaries
                    );
                });

                $this->scale_push($diffed);
                $this->origin = $this->shared;

                return $this;
            }

            if ($this->timestamp !== false) { # updated_at
                $diffed[$this->scale_column($this->created_at)] = $this->scale_timestamp(); 
            }

            $adapter = static::adapter();

            $adapter->query(function($query) use ($diffed) {
                $query->insert(
                    $this->table
                )->set($diffed);
            });

            # creation
            if ($this->watch) {
                $diffed[$this->scale_increment()] = $adapter->id();
            }

            $this->scale_push($diffed); 
            $this->origin = $this->shared;

            $this->intable = true;
        }

        return $this;
    }

    public function delete()
    {
        $diffed     = array();
        $primaries  = $this->scale_primary($diffed);

        if ($this->intable) {
            static::query(function($query) use ($primaries) {
                $query->delete()->from(
                    $this->table
                )->where(
                    $primaries
                );
            });

            $this->shared   = $this->origin = array();
            $this->intable  = false;

            unset($this);
        }

        return null;
    }

    public static function transaction($transaction, &$exception)
    {
        return static::adapter()->transaction($transaction, $exception);
    }

    public static function query($query)
    {
        return static::adapter()->query($query);
    }

    public static function adapter()
    {
        return Database::get(static::$adapter);
    }

    public function __array()
    {
        return $this->shared;
    }

    public function __call($method, $argument)
    {
        if ($method === 'array') {
            return $this->__array();
        }
    }

    /**
     *  Compile array to ActiveRecord model.
     * 
     *  @var array $collection
     *  @var bool  $arrayable
     *
     *  @return mixed
     */
    protected function compile_one($collection, $arrayable = false)
    {
        if ($arrayable) {
            return empty($dataset) ? null : $dataset;
        }

        if (
            !empty($collection)
        ) {
            $this->scale_push($collection);
            $this->origin = $this->shared;

            return $this;
        }

        return null;
    }
    /**
     *  Compile array to ActiveRecord collection of model.
     * 
     *  @var array $collection
     *  @var bool  $arrayable
     *
     *  @return mixed
     */
    protected function compile_all($collection, $arrayable = false, $container = array())
    {
        if ($arrayable) {
            return empty($dataset) ? null : $dataset;
        }

        if (
            !empty($collection)
        ) {
            foreach ($collection as $item) {
                array_push(
                    $container, clone $this->compile_one($item)
                );
            }

            return $container;
        }

        return null;
    }

    use ActiveRecordScaleableTrait;
}
