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
 *  Writeable Essence Abstract
 *
 *  @subpackage Essence
 */
abstract class WriteableAbstract extends ReadableAbstract
{
    /**
     *  Set container by key.
     *
     *  @var mixed $keys
     *  @var mixed $equal
     *
     *  @return \Panda\Essence\WriteableAbstract
     */
    public function set($keys, $equal = null)
    {
        if ($this->scalar() === true) {
             $this->shared = $keys;

            return $this;
        }

        if (is_array($keys) && $equal === null) {
            $this->shared = array_replace($this->shared, $keys);
        } else {
            $this->shared[$keys] = $equal;
        }

        return $this;
    }

    /**
     *  Set magic shared by key.
     *
     *  @var scalar $key
     *  @var mixed  $equal
     *
     *  @return \Panda\Essence\WriteableAbstract
     */
    public function __set($key, $equal)
    {
        if ($this->scalar() === true) {
             return $this->set($equal);
        }

        return $this->set($key, $equal);
    }

    /**
     *  Set shared array data.
     *
     *  @var array $shared
     *
     *  @return \Panda\Essence\WriteableAbstract
     */
    public function replace($shared = [])
    {
        if ($this->scalar() === true) {
            $this->set($shared);
        
            return $this;
        }

        $this->shared = array_replace($this->shared, $shared);

        return $this;
    }

    /**
     *  @var mixed $key
     *  @var mixed $equal
     *
     *  @return \Panda\Essence\WriteableAbstract
     */
    public function push($keys, $equal = null)
    {
        if ($this->scalar() === true) {
            return $this->set($keys);
        }

        $makeable = function($key, $equal) {
            if (strpos($key, '.') === false) {
                $this->shared[$key] = $equal;
            } else {   
                $this->shared = $this->__push(($chain = explode('.', $key)), $equal, 0, count($chain), $this->shared);
            }
        };

        if (is_array($keys) && $equal === null) {
            foreach ($keys as $key => $equal) {
                call_user_func($makeable, $key, $equal);
            }

            return $this;
        }

        call_user_func($makeable, $keys, $equal);

        return $this;
    }

    /**
     *  @var array   $key
     *  @var mixed   $value
     *  @var numeric $index
     *  @var numeric $limit
     *  @var array   $source
     *
     *  @return \Panda\Essence\WriteableAbstract
     */
    public function __push(array $keys, $value, $index, $limit = 0, $source)
    {
        if ($index >= $limit) return $value;
        $source[$keys[$index]] = call_user_func(
            __METHOD__, $keys, $value, $index + 1, $limit, isset($source[$keys[$index]]) ? $source[$keys[$index]] : []
        );

        return $source;
    }
}
