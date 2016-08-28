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
abstract class ActiveRecord extends EssenceWriteableAbstract implements ActiveRecordInterface 
{
    /**
     *  @var string
     */ 
    protected $adapter      = 'default';

    /**
     *  @var string
     */ 
    protected $table        = null;

    /**
     *  String - one primary. Array - pair oriented.
     *
     *  @var mixed
     */ 
    protected $primary      = 'id';

    /**
     *  Basic scale implementation of one autoincrements.
     *
     *  @var mixed
     */ 
    protected $increment    = 'id';

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
                $this->shared[$shared] = $columns[$origin]; 
            }

            return;
        }

        array_replace($this->shared, $columns);
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
                # update schema event
                if (
                    isset($diffed[$primary])
                ) {
                    unset($diffed[$primary]);
                } 

                $collection[$primary] = $this->origin[$primary];
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
        $factory = static::factory();
        $factory->intable   = true;

        return new static;
    }

    public static function one($array = false)
    {
        $factory = static::factory();
        $factory->intable   = true;

        return new static;
    }

    public static function by($array = false)
    {
        $factory = static::factory();
        $factory->intable   = true;

        return new static;
    }

    public static function all($array = false)
    {
        $factory = static::factory();
        $factory->intable   = true;

        return new static;
    }

    public function touch()
    {
        if ($this->intable && $this->timestamp) {
            $diffed  = array(
                $this->scale_column($this->updated_at) => $this->scale_timestamp()
            );

            $primary = $this->scale_primary($diffed);

            # update
        }
    }

    public function update(array $shared)
    {
        return $this->add($shared)->save();
    }

    public function save()
    {
        $diffed  = $this->scale_pull();
        $primary = $this->scale_primary($diffed);

        if (
            !empty($diffed)
        ) {
            if ($this->intable) {
                if ($this->timestamp) { # updated_at
                    $diffed[$this->scale_column($this->updated_at)] = $this->scale_timestamp(); 
                }
                var_dump($diffed, $this->intable); die;
                # update

                return $this;
            }

            if ($this->timestamp) { # updated_at
                $diffed[$this->scale_column($this->created_at)] = $this->scale_timestamp(); 
            }

            # creation
            if ($this->watch) {
                $this->scale_increment();
                # increment syncronize, and update by
            }

            $this->intable = true;
        }

        return $this;
    }

    public function delete()
    {
        $diffed  = array();
        $primary = $this->scale_primary($diffed);

        if ($this->intable) {
            # delete
        }
    }

    public function query($query)
    {
        return $this->adapter()->query($query);
    }

    public function adapter()
    {
        return Database::get($this->adapter);
    }
}
