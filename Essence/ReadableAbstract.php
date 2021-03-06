<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Essence;

/**
 *  Essence Readable Abstract
 *
 *  @subpackage Essence
 */
abstract class ReadableAbstract
{

    /**
     *  @const HASH
     */
    const HASH          = 'hash';

    /**
     *  @const SCALAR
     */
    const SCALAR        = 'scalar';

    /**
     *  @var string
     */
    protected $type     = 'hash';

    /**
     *  @var string
     */
    protected $serial   = 'json';

    /**
     *  @var string
     */
    protected $crypt    = true;

    /**
     *  @var array
     */
    protected $shared   = [];


    /**
     *  @return boolean
     */
    public function hash()
    {
        return $this->type === static::HASH;
    }

    /**
     *  @return boolean
     */
    public function scalar()
    {
        return $this->type === static::SCALAR;
    }

    /**
     *  @return boolean
     */
    protected function encode()
    {
        return DataStruct::encode(
            $this->shared, $this->serial, $this->type, $this->crypt
        );
    }

    /**
     *  @return boolean
     */
    protected function decode($data)
    {
        $this->shared = DataStruct::decode(
            $data, $this->serial, $this->type, $this->crypt
        );

        return $this;
    }

    /**
     *  Get shared value by key.
     *
     *  @var string $key
     *  @var mixed  $default
     *
     *  @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->scalar() === true) {
            return $this->shared;
        }

        return array_key_exists($key, $this->shared) ? $this->shared[$key] : $default;
    }

    /**
     *  Get shared values by keys.
     *
     *  @var mixed $keys
     *  @var mixed $fillable
     *
     *  @return array
     */
    public function only($keys, $fillable = null)
    {
        if ($this->scalar() === true) {
            return $this;
        }

        if (!is_array($keys)) {
            $keys       = func_get_args();
            $fillable   = null;
        }

        $arrayable = array_intersect_key(
            $this->shared, array_flip($keys)
        );

        if ($fillable === null) {
            return $arrayable;
        }

        return array_replace(
            array_combine($keys, $fillable), $arrayable
        );
    }

    /**
     *  Get shared values which not in key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function except($keys)
    {
        if ($this->scalar() === true) {
            return $this->shared;
        }

        $keys = is_array($keys) ? $keys : func_get_args();

        return array_diff_key(
            $this->shared, array_flip($keys)
        );
    }

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function exists($keys)
    {
        if ($this->scalar() === true) {
            return isset($this->shared);
        }

        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    /**
     *  Check if one key exist.
     *
     *  @var scalar $key
     *
     *  @return bool
     */
    public function has($key)
    {
        if ($this->scalar() === true) {
             return isset($this->shared);
        }

        return array_key_exists(
            $key, $this->shared
        );
    }

    /**
     *  Return shared
     *
     *  @return array
     */
    public function data(callable $iterator = null)
    {
        if ($iterator !== null) {
            foreach ($this->shared as $key => $value) {
                call_user_func($iterator, $key, $value);
            }
        }

        return $this->shared;
    }

    /**
     *  Magic override get.
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     *  Magic override has.
     *
     *  @var string $key
     *
     *  @return bool
     */
    public function __isset($key)
    {
        if ($this->scalar() === true) {
             return isset($this->shared);
        }

        return array_key_exists($key, $this->shared);
    }

    /**
     *  Pull multidemential array using \. modifier
     *
     *  @var strict $key
     *
     *  @return bool
     */
    public function pull($key)
    {
        if ($this->scalar() === true) {
             return $this->shared;
        }

        if (strpos($key, '.') === false) {
            return array_key_exists($key, $this->shared) ? $this->shared[$key] : null;
        }

        return $this->__pull(
            ($chain = explode('.', $key)), 0, count($chain), $this->shared
        );
    }

    /**
     *  Recursion of pull action.
     *
     *  @var array      $chain
     *  @var numeric    $index
     *  @var numeric    $limit
     *  @var mixed     $shared
     *
     *  @return mixed
     */
    protected function __pull(array $chain, $index, $limit = null, $shared)
    {
        if ($index >= $limit) return $shared;

        if (array_key_exists($index, $chain) && array_key_exists($chain[$index], $shared)) {
            return call_user_func(
                __METHOD__, $chain, $index + 1, $limit, $shared[$chain[$index]]
            );
        }

        return null;
    }
}
