<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Database;

use Panda\Database\Quering;

use Panda\Form\DataAbstract         as Form;
use Panda\Essence\WriteableAbstract as Essence;

/**
 *  Panda Academic
 *
 *  @subpackage Framework
 */
abstract class Intelligent extends Essence
{
    /**
     *  @var string
     */
    protected $adapter      = 'default';

    /**
     *  @var string
     */
    protected $table;

    /**
     *  @var bool
     */
    protected $increment    = true;

    /**
     *  @var mixed
     */
    protected $timestamp    = false;

    /**
     *  @var mixed
     */
    protected $primary      = 'id';

    /**
     *  @var string
     */
    protected $datetime     = 'Y-m-d H:i:s';

    /**
     *  @var string
     */
    protected $date         = 'Y-m-d';

    /**
     *  @var string
     */
    protected $time         = 'H:i:s';

    /**
     *  @var array
     */
    protected $shared       = [];

    /**
     *  @var array
     */
    protected $origin       = [];

    /**
     *  @var string
     */
    protected $created_at   = 'create_time';

    /**
     *  @var string
     */
    protected $updated_at   = 'update_time';

    /**
     *  @var boolean
     */
    protected $isset        = false;

    public static function factory()
    {
        return new static();
    }

    public static function create($shared = null, $equal = null)
    {
        $unit = static::factory();

        $unit->objected($shared, $equal);

        return $unit->insert(
            $shared, $equal
        );
    }

    /**
     *  Custom request template for select one unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */
    public static function one_query(callable $callback)
    {
        $fetch = (
            $self = static::factory()
        )->query(
            function($q) use ($callback, $self) {
                call_user_func($callback, $q, $self);
            }
        )->one();

        if (empty($fetch) === true) return null;

        $self->isset = true;

        return $self->originate($fetch);
    }

    public static function find($primary)
    {
        return static::one_query(function($q, $self) use ($primary) {
            $where = is_array($primary) ? $primary : [
                $self->primary => $primary
            ];

            $self->select(
                $q
            )->where(
                $self->primary($where)
            )->limit(1);
        });
    }

    /**
     *  Use template for select one unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */
    public static function one($condition = null, $order = null, $offset = null)
    {
        return static::one_query(
            function($q, $self) use ($condition, $order, $offset) {
                $self->select($q)
                    ->where($condition)
                    ->order($order)
                    ->limit(1, $offset);
            }
        );
    }

    /**
     *  Custom method for selection array of unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */
    public static function by_query(callable $callback)
    {
        $fetches = (
            $self = static::factory()
        )->query(
            function($q) use ($callback, $self) {
                call_user_func($callback, $q, $self);
            }
        )->all();

        if (empty($fetches) === true) return null;

        $collection = [];

        $self->isset = true;

        foreach ($fetches as $fetch) {
            array_push($collection, clone $self->originate($fetch));
        }

        return $collection;

    /**
     *  Custom method for selection array of unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */    }

    public static function by($condition = null, $order = null, $limit = null, $offset = null)
    {
        return static::by_query(
            function($q, $self) use ($condition, $order, $limit, $offset) {
                $self->select($q)
                    ->where($condition)
                    ->order($order)
                    ->limit($limit, $offset);
            }
        );
    }

    /**
     *  Custom method for selection all of unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */
    public static function all()
    {
        return static::by_query(
            function($q, $self) {
                $self->select($q);
            }
        );
    }

    /**
     *  Custom method for selection array of unit
     *
     *  @param  callable $callback
     *
     *  @return mixed
     */
    public function select(Quering $query = null, $column = ['*'])
    {
        $query = $query === null ? Quering::factory() : $query;

        return $query->select($column)->from($this->table);
    }

    public function insert($shared = null, $equal = null)
    {
        if ($this->isset === true) return $this;

        if ($shared !== null) {
            $this->replace(
                is_array($shared) ? $shared : [$shared => $equal]
            );
        }

        if ($this->timestamp !== false) {
            $this->timestamp(true, 'created_at');
        }

        $this->query(function($q) {
            $q->insert($this->table)->set($this->shared);
        });

        if ($this->increment === true) {
            $this->{$this->primary} = $this->adapter()->id();
        }

        $this->originate();

        $this->isset = true;

        return $this;
    }

    public function update($shared = null, $equal = null)
    {
        if ($this->isset === false) return $this;

        $this->objected($shared, $equal);

        if ($shared !== null) {
            $this->replace(
                is_array($shared) ? $shared : [$shared => $equal]
            );
        }

        if ($this->timestamp !== false) {
            $this->timestamp(true, 'updated_at');
        }

        if (is_array($this->primary)) {
            $primary = [];

            foreach ($this->primary as $pk) {
                $primary[$pk] = $this->get($pk);
            }
        } else {
            $primary    = [$this->primary => $this->get($this->primary)];
        }

        $shared = array_replace($this->diff(), $primary); $condition = $this->primary($shared);

        if (empty($shared) === true) {
            return $this;
        }

        $this->query(function($q) use ($shared, $condition) {
            $q->update($this->table)->set($shared)->where($condition);
        });

        $this->originate();

        return $this;
    }

    public function delete()
    {
        if ($this->isset === false) return $this;

        $shared = $this->origin; $condition = $this->primary($shared);

        $this->query(function($q) use ($condition) {
            $q->delete()->from($this->table)->where($condition);
        });

        $this->isset = false;

        return $this;
    }

    public function save()
    {
        if ($this->isset === false) {
            return $this->insert();
        }

        return $this->update();
    }

    public function remove()
    {
        return $this->delete();
    }

    public function table()
    {
        return $this->table;
    }

    /**
     *
     *
     *  @param  mixed $shared
     *
     *  @return Intelligent
     */
    public function fill($shared)
    {
        $this->objected($shared, $equal);

        $this->replace($shared);

        return $this;
    }

    public function diff()
    {
        return array_diff_assoc($this->shared, $this->origin);
    }

    public function primary(&$shared = null)
    {
        $condition = [];
        $primaries = is_array($this->primary) ? $this->primary : [$this->primary];

        foreach ($primaries as $primary) {
            if (array_key_exists($primary, $shared)) {
                $condition[$primary] = $shared[$primary]; unset($shared[$primary]);
            }
        }

        return $condition;
    }

    public function originate($shared = null)
    {
        if ($shared !== null) {
            $this->shared = $shared;
        }

        $this->origin = $this->shared;

        return $this;
    }

    public function timestamp($formated = false, $property = null)
    {
        if ($formated === false) {
            return $this->timestamp;
        }

        if ($this->timestamp === true || $this->timestamp === 'datetime') {
            $timestamp = date($this->datetime);
        }

        if ($this->timestamp === 'date') {
            $timestamp = date($this->date);
        }

        if ($this->timestamp === 'time') {
            $timestamp = date($this->time);
        }

        if ($this->timestamp === 'unix') {
            $timestamp = date('U');
        }

        if ($property === null) {
            return $timestamp;
        }

        $this->set($this->{$property}, $timestamp);

        return $this;
    }

    public function transaction(callable $callback, &$error = null)
    {
        return $this->adapter()->transaction(
            $callback, $error
        );
    }

    public function query($q)
    {
        return $this->adapter()->query($q);
    }

    public function adapter()
    {
        static $adapter = null;

        if ($adapter === null) {
            //
            $adapter = Manager::get($this->adapter);
        }

        return $adapter;
    }

    protected function objected(&$shared, $equal = null)
    {
        if (is_object($shared) && is_subclass_of($shared, Form::class) === true)
            $shared = is_array($equal) === false ? $shared->all() : array_replace(
                $shared->all(), $equal
            );
    }
}
