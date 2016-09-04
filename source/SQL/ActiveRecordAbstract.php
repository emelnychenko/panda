<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\SQL;
  
use Panda\Essence\WirteableAbstract     as Essence;
use Panda\Joint\SQL                     as SQL;

/**
 *  Panda Bootstrap
 *
 *  @subpackage Framework
 */
abstract class ActiveRecordAbstract extends Essence
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
        return SQL::get(static::$adapter);
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

    protected function scale_fill()
    {
        $natived        = array_values($this->columns);

        $scalable       = array_combine(
            $natived, $natived
        );

        $replace        = array_filter(
            array_flip($this->columns), 'is_string'
        );

        $this->columns  = array_flip(
            array_replace($scalable, $replace)
        );
    }

    /**
     *  On selection action push to shared
     */ 
    protected function scale_push($columns)
    {
        if (
            !empty($this->columns)
        ) {
            foreach($this->columns as $shared => $origin) {
                if (
                    array_key_exists($origin, $columns)
                ) {
                    $this->shared[$shared] = $columns[$origin];
                } 
            }

            return $this->shared;
        }

        $this->shared = array_replace($this->shared, $columns);

        return $this->shared;
    }
    

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_pull()
    {
        $diffed = array_diff_assoc($this->shared, $this->origin);

        if (
            !empty($this->columns)
        ) {
            $diffed = array_intersect_key($diffed, $this->columns);
            $shared = array();

            foreach($diffed as $scale => $equal) {
                $shared[$this->columns[$scale]] = $equal;
            }

            return $shared;
        }

        return $diffed;
    }

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_primary(&$diffed)
    {
        # where clause implementation ...
        $collection = array(); 
        $primaries  = is_array($this->primary) ? $this->primary : array($this->primary);

        foreach($primaries as $scale) {
            $primary = array_key_exists($scale, $this->columns) ? $this->columns[$scale] : $scale;

            if ($this->intable) {
                if (
                    isset($diffed[$primary])
                ) {
                    unset($diffed[$primary]);
                }

                $collection[$primary] = $this->origin[$scale];
            } elseif ($this->watch) {
                # insert schema event
                unset($diffed[$primary]);
            }
        }

        return $collection;
    }

    /**
     *  On insert, update action pull from diff shared with origin
     */ 
    protected function scale_increment()
    {
        return $this->scale_column($this->increment);
    }

    protected function scale_column($column)
    {
        return array_key_exists($column, $this->columns) ? $this->columns[$column] : $column;
    }

    protected function scale_selection($condition)
    {
        $exchanged = array();

        if (
            !empty($this->columns)
        ) {
            foreach ($condition as $column => $equal) {
                $exchanged[$this->columns[$column]] = $equal;
            }
        } else {
            $exchanged = $condition;
        }

        return $exchanged;
    }

    /**
     *  Build timestamp format for auto setter.
     */ 
    protected function scale_timestamp()
    {
        if ($this->timestamp === true || $this->timestamp === 'datetime') {
            return date($this->datetime);
        } elseif ($this->timestamp === 'date') {
            return date($this->date);
        } elseif ($this->timestamp === 'time') {
            return date($this->time);
        } elseif ($this->timestamp === 'unix') {
            return date('U');
        }

        return date($this->datetime);
    }
}
