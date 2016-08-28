<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Readable Container Abstract
 *
 *  @subpackage Foundation
 */
abstract class EssenceReadableAbstract implements EssenceReadableInterface
{
    /**
     *  @var array
     */
    protected $shared = array();

    /**
     *  @var array
     */
    protected $origin = array();

    /**
     *  Get container value by asoociation key.
     *
     *  @var string $key
     *  @var mixed  $default
     *
     *  @return mixed
     */
    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->shared) ? $this->shared[$key] : $default;
    }

    /**
     *  Get shared values by key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function only($keys, $defaults = null)
    {
        if (
            !is_array($keys)
        ) {
            $keys = func_get_args(); $defaults = null;
        }

        $collection = array_intersect_key(
            $this->shared, array_flip($keys)
        );

        if (
            isset($defaults)
        ) {
            $combining = array_combine($keys, $defaults);

            foreach($combining as $key => $default) {
                if (
                    !isset($collection[$key])
                ) {
                    $collection[$key] = $default;
                }
            }
        }

        return $collection;
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
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
    }

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function has($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return sizeof($keys) === sizeof(
            $this->only($keys)
        );
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
        return array_key_exists($key, $this->shared);
    }

    /**
     *  Need Addition Implementation
     */ 
    protected function __pull($key, $source)
    {
        $response = null; $key = is_numeric($key) ? $equal : $key;

        if (strpos($key, '.') !== false) {
            $keychain = explode('.', $key);
            $response = $this->__pull_r($keychain, 0, count($keychain), $source);
        } elseif (
            isset($source[$key])
        ) {
            $response = $source[$key];
        }
        
        return is_callable($response) ? call_user_func($response) : $response;
    }

    protected function __pull_r(array $keys, $index, $limit = 0, $source)
    {
        if ($index >= $limit) return $source;
            
        return isset($source[$keys[$index]]) ? $this->__pull_r(
            $keys, $index + 1, $limit, $source[$keys[$index]]
        ) : null;
    }
}
