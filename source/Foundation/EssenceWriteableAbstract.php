<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Writeable shared Abstract
 *
 *  @subpackage Foundation
 */
abstract class EssenceWriteableAbstract extends EssenceReadableAbstract implements EssenceWriteableInterface
{
    /**
     *  Set shared array data.
     *
     *  @var array $shared
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function add(array $shared = null)
    {
        $this->shared = array_replace($this->shared, $shared);

        return $this;
    }

    /**
     *  Set shared by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function set($key, $equal)
    {
        $this->shared[$key] = $equal;

        return $this;
    }

    /**
     *  Set magic shared by key.
     *
     *  @var array $key
     *  @var array $equal
     *
     *  @return \Panda\Foundation\Support\EssenceWriteableAbstract
     */
    public function __set($key, $equal)
    {
        return $this->set($key, $equal);
    }

    /**
     *  Need Addition Implementation
     */
    protected function __push($key, $equal = null, array &$source = [])
    {
        if (strpos($key, '.') !== false) {
            $keychain   = explode('.', $key);
            $source     = $this->__push_r($keychain, $equal, 0, count($keychain), $source);
        } else {
            $source[$key] = $equal;
        }
        return $source;
    }

    protected function __push_r(array $keys, $value, $index, $limit = 0, $source)
    {
        if ($index >= $limit) return $value;
        $source[$keys[$index]] = $this->__push_r(
            $keys, $value, $index + 1, $limit, isset($source[$keys[$index]]) ? $source[$keys[$index]] : []
        );

        return $source;
    }

}
