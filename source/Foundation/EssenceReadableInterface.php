<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation;

/**
 *  Readable Container Interface
 *
 *  @subpackage Foundation
 */
interface EssenceReadableInterface
{
    /**
     *  Get container value by asoociation key.
     *
     *  @var string $key
     *  @var mixed  $default
     *
     *  @return mixed
     */
    public function get($key, $default = null);

    /**
     *  Get container values by key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function only($keys, $defaults = null);

    /**
     *  Get container values which not in key array.
     *
     *  @var mixed $keys
     *
     *  @return array
     */
    public function except($keys);

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function exists($keys);

    /**
     *  Chech if all keys exist in $cintainer.
     *
     *  @var mixed $keys
     *
     *  @return bool
     */
    public function has($keys);

    /**
     *  Magic override get.
     *
     *  @var string $key
     *
     *  @return mixed
     */
    public function __get($key);

    /**
     *  Magic override has.
     *
     *  @var string $key
     *
     *  @return bool
     */
    public function __isset($key);
}
