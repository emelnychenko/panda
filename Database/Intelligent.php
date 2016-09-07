<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Database;

use Panda\Database\Quering;

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
    protected $created_at   = 'created_at';

    /**
     *  @var string
     */
    protected $updated_at   = 'updated_at';

    /**
     *  @var string
     */
    protected $indatabase   = false;

    public static function factory()
    {
        return new static();
    }

    public static function create($shared = null, $equal = null)
    {
        return static::factory()->insert($shared, $equal);
    }

    public static function find($primary)
    {
        $factory = static::factory();

        $primary = is_array($primary) ? $primary : [$factory->primary => $primary];
        $primary = $factory->primary($primary);

        $result  = $factory->query(function($q) use ($factory, $primary) {
            $factory->select($q)->where($primary)->limit(1);
        })->one();

        if (empty($result) === true) {
            return null;
        }

        $factory->indatabase === true;

        return $factory->originate($result);
    }

    public static function one($condition = null, $order = null, $offset = null)
    {
        $factory = static::factory();

        $result  = $factory->query(function($q) use ($factory, $condition, $order, $offset) {
            $factory->select($q)->where($condition)->order($order)->limit(1, $offset);
        })->one();

        if (empty($result) === true) {
            return null;
        }

        $factory->indatabase === true;

        return $factory->originate($result);
    }

    public static function by($condition = null, $order = null, $limit = null, $offset = null)
    {
        $factory = static::factory(); $factories = [];

        $results = $factory->query(function($q) use ($factory, $condition, $order, $limit, $offset) {
            $factory->select($q)->where($condition)->order($order)->limit($limit, $offset);
        })->all();

        if (empty($results) === true) {
            return null;
        }

        $factory->indatabase === true;

        foreach ($results as $result) {
            array_push($factories, clone $factory->originate($result));
        }

        return $factories;
    }

    public static function all()
    {
        $factory = static::factory(); $factories = [];

        $results = $factory->query(function($q) use ($factory) {
            $factory->select($q);
        })->all();

        if (empty($results) === true) {
            return null;
        }

        $factory->indatabase === true;

        foreach ($results as $result) {
            array_push($factories, clone $factory->originate($result));
        }

        return $factories;
    }

    public function select(Quering $query = null, $column = ['*'])
    {
        $query = $query === null ? Quering::factory() : $query; 
        
        return $query->select($column)->from($this->table);
    }

    public function insert($shared = null, $equal = null)
    {
        if ($this->indatabase === true) {
            return $this;
        }

        if ($shared !== null) {
            $this->replace(is_array($shared) ? $shared : [$shared => $equal]);
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

        $this->indatabase === true;

        return $this;
    }

    public function update($shared = null, $equal = null)
    {
        if ($this->indatabase === false) {
            return $this;
        }

        if ($shared !== null) {
            $this->replace(is_array($shared) ? $shared : [$shared => $equal]);
        }

        if ($this->timestamp !== false) {
            $this->timestamp(true, 'updated_at');
        }

        $shared = $this->diff(); $condition = $this->primary($shared);

        if (empty($shared) === true) {
            return $this;
        }

        $this->query(function($q) use ($shared) {
            $q->update($this->table)->set($shared)->where($condition);
        });

        $this->originate();

        return $this;
    }

    public function delete()
    {
        if ($this->indatabase === false) {
            return $this;
        }

        $shared = $this->shared; $condition = $this->primary($shared);

        $this->query(function($q) use ($shared) {
            $q->delete()->from($this->table)->where($condition);
        });

        $this->indatabase === false;

        return $this;
    }

    public function save()
    {
        if ($this->indatabase === false) {
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

    public function fill($shared)
    {
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

    public function transaction(callable $callback)
    {
        return $this->adapter()->transaction($callback);
    }

    public function query($q)
    {
        return $this->adapter()->query($q);
    }

    public function adapter()
    {
        $adapter = Manager::get($this->adapter);

        if ($adapter === null) {
            //
        }

        return $adapter;
    }
}